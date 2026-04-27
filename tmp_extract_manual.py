import re
from pypdf import PdfReader

pdf_path = r'public/Seventh-day-Adventist-Church-Manual-2025.pdf'
reader = PdfReader(pdf_path)
full_text = []
for page in reader.pages:
    t = page.extract_text() or ''
    full_text.append(t)
text = '\n'.join(full_text)
text = re.sub(r'\s+', ' ', text)

terms = [
    'Pastor', 'Elder', 'Head deacon', 'Head deaconess', 'Treasurer', 'Clerk',
    'Personal Ministries', 'Women\'s Ministries', 'Children\'s Ministries', 'Family Ministries',
    'Publishing Ministries', 'Sabbath School', 'Adventist Youth', 'Pathfinder', 'Adventurer',
    'Education', 'Stewardship', 'Religious Liberty', 'Communication', 'Community Services'
]

for term in terms:
    idx = text.lower().find(term.lower())
    print('\n===== ' + term + ' =====')
    if idx == -1:
        print('NOT FOUND')
        continue
    start = max(0, idx - 320)
    end = min(len(text), idx + 520)
    print(text[start:end])
