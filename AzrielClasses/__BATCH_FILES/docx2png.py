import sys
import os
import tempfile
import argparse


def docx_to_png(input_path, output_dir=None, dpi=150):
    try:
        import win32com.client
    except ImportError:
        print("Error: pywin32 not installed. Run: pip install pywin32")
        sys.exit(1)

    try:
        import fitz
    except ImportError:
        print("Error: PyMuPDF not installed. Run: pip install pymupdf")
        sys.exit(1)

    input_path = os.path.abspath(input_path)
    basename = os.path.splitext(os.path.basename(input_path))[0]

    if output_dir is None:
        output_dir = os.path.dirname(input_path)
    os.makedirs(output_dir, exist_ok=True)

    tmp_pdf = tempfile.mktemp(suffix=".pdf")
    word = win32com.client.Dispatch("Word.Application")
    word.Visible = False

    try:
        doc = word.Documents.Open(input_path)
        doc.ExportAsFixedFormat(tmp_pdf, 17)  # 17 = wdExportFormatPDF
        doc.Close(False)
    finally:
        word.Quit()

    pdf = fitz.open(tmp_pdf)
    output_files = []
    mat = fitz.Matrix(dpi / 72, dpi / 72)

    for i, page in enumerate(pdf):
        pix = page.get_pixmap(matrix=mat)
        if len(pdf) == 1:
            out_path = os.path.join(output_dir, f"{basename}.png")
        else:
            out_path = os.path.join(output_dir, f"{basename}_page_{i+1:03d}.png")
        pix.save(out_path)
        output_files.append(out_path)
        print(f"Saved: {out_path}")

    pdf.close()
    os.unlink(tmp_pdf)
    return output_files


if __name__ == "__main__":
    parser = argparse.ArgumentParser(description="Convert DOCX to PNG using Microsoft Word")
    parser.add_argument("input", help="Path to .docx file")
    parser.add_argument("output_dir", nargs="?", help="Output directory (default: same as input)")
    parser.add_argument("--dpi", type=int, default=150, help="Resolution in DPI (default: 150)")
    args = parser.parse_args()

    if not os.path.isfile(args.input):
        print(f"Error: file not found: {args.input}")
        sys.exit(1)

    docx_to_png(args.input, args.output_dir, args.dpi)
