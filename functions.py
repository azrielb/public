import openpyxl
import csv

def xlsx2csv(filepath):
    wb = openpyxl.load_workbook(filepath)
    sheet = wb.active
    with open(filepath + ".csv", "w", newline="", encoding="utf-8") as f:
        writer = csv.writer(f)
        for row in sheet.iter_rows(values_only=True):
            writer.writerow(row)
    print(filepath + ".csv" + " has been created")
    
    
def excelColToNum(col):
    col = col.upper()
    num = 0
    for i in range(len(col)):
        num *= 26
        num += ord(col[i]) - ord('A') + 1
    return num
    
