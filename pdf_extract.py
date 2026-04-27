from pathlib import Path

pdf = Path('Church.-Board-QSG.pdf')
out = Path('storage/app/pdf_extract')
out.mkdir(parents=True, exist_ok=True)
text_path = out / 'church_board_text.txt'
img_index_path = out / 'church_board_images.txt'

try:
    from pypdf import PdfReader
except Exception:
    import subprocess
    import sys

    subprocess.check_call([sys.executable, '-m', 'pip', 'install', 'pypdf'])
    from pypdf import PdfReader

reader = PdfReader(str(pdf))

with text_path.open('w', encoding='utf-8') as f:
    for i, page in enumerate(reader.pages, 1):
        f.write(f'===== PAGE {i} =====\n')
        txt = page.extract_text() or ''
        f.write(txt)
        f.write('\n\n')

with img_index_path.open('w', encoding='utf-8') as f:
    for i, page in enumerate(reader.pages, 1):
        images = getattr(page, 'images', [])
        f.write(f'PAGE {i}: {len(images)} images\n')
        for j, img in enumerate(images, 1):
            name = getattr(img, 'name', f'image_{j}')
            ext = getattr(img, 'image_format', None) or 'bin'
            data = img.data
            out_file = out / f'page_{i:02d}_{j:02d}_{name}.{ext}'
            out_file.write_bytes(data)
            f.write(f'  - {out_file.name}\n')

print('done')
