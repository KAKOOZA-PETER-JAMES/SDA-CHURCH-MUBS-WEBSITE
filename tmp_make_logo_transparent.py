from PIL import Image

src = r"c:\Users\STUDENTS\Desktop\sda\public\sda logo.png"
out = r"c:\Users\STUDENTS\Desktop\sda\public\sda-logo-transparent.png"

img = Image.open(src).convert("RGBA")
pixels = img.getdata()
new_pixels = []

for r, g, b, a in pixels:
    # Remove near-white background while preserving logo details
    if r > 240 and g > 240 and b > 240:
        new_pixels.append((r, g, b, 0))
    else:
        new_pixels.append((r, g, b, a))

img.putdata(new_pixels)
img.save(out, "PNG")
print(out)
