import os
import re

file_extensions = ".cpp .cs .h".split()
words_dictionary = {
    'word': 'replacement',
}
folders = ['C:\\workspace\\a\\b\\c\\' + x for x in '1 2 3'.split()]
folders += ['C:\\workspace\\b\\' + x for x in '1 2 3'.split()]

def replace_in_file(file_path):
    try:
        with open(file_path, 'r') as file:
            content = orig_content = file.read()
        for k,v in words_dictionary.items():
            content = re.sub(k, v, content)
        if orig_content != content:
            print(file_path)
            with open(file_path, 'w') as file:
                file.write(content)
    except Exception:
        print(f"problem in file {file_path}")
        raise

def replace_in_folder(folder_path):
    for root, dirs, files in os.walk(folder_path):
        if any(x in root for x in r'\out\build \obj\Debug obj\Release'.split()):
            continue
        for file in files:
            if any(file.endswith(ext) for ext in file_extensions):
                file_path = os.path.join(root, file)
                replace_in_file(file_path)

for folder in folders:
    replace_in_folder(folder)
