import re
import quopri
import json
import pathlib

p = pathlib.Path(r'c:\Users\STUDENTS\Desktop\sda\public\view-source_https___adventist.org_beliefs.mhtml')
raw = p.read_bytes()
dec = quopri.decodestring(raw).decode('utf-8', 'ignore')

m = re.search(r'<script id="__NEXT_DATA__" type="application/json">(.*?)</script>', dec, re.S)
if not m:
    print('NO_NEXT_DATA')
    raise SystemExit

data = json.loads(m.group(1))

beliefs = []
def walk(obj):
    if isinstance(obj, dict):
        ident = obj.get('id')
        if isinstance(ident, str) and re.fullmatch(r'belief-\d+', ident):
            beliefs.append(obj)
        for value in obj.values():
            walk(value)
    elif isinstance(obj, list):
        for item in obj:
            walk(item)
walk(data)

seen = set()
unique_beliefs = []
for item in beliefs:
    ident = item['id']
    if ident not in seen:
        seen.add(ident)
        unique_beliefs.append(item)


def collect_text(node):
    output = []
    def rec(n):
        if isinstance(n, dict):
            text_value = n.get('text')
            if isinstance(text_value, str):
                output.append(text_value)
            for value in n.values():
                rec(value)
        elif isinstance(n, list):
            for child in n:
                rec(child)
    rec(node)
    text = ' '.join(chunk.strip() for chunk in output if chunk and chunk.strip())
    return re.sub(r'\s+', ' ', text).strip()

print('BELIEF_COUNT', len(unique_beliefs))
for belief in sorted(unique_beliefs, key=lambda x: int(x['id'].split('-')[1])):
    title = belief.get('name', '')
    body = collect_text(belief)
    if body.startswith(title):
        body = body[len(title):].strip(' .')
    print('---')
    print(title)
    print(body[:700])
