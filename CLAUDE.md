# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Repository Overview

This is a personal developer toolbox containing utility libraries, automation scripts, and educational projects across multiple languages (C#, C++, Python, PHP, JavaScript, Windows batch).

## Project Structure

### AzrielClasses/
Multi-language utility library with Visual Studio 2010 solution.

**Build:** Open `AzrielClasses/AzrielClasses.sln` in Visual Studio. Contains C# library (.NET Framework 4.0) and C++ projects.

- **AzrielClasses/AzrielClasses/** - C# utility library with string manipulation, validation (Israeli ID, email), swap operations, time calculations
- **AzrielClasses/CPP/** - C++ data structures: Huffman coding (`HuffmanTree.h/cpp`), B+ tree (`BPlusTree.h`), Graph (`Graph.h`)
- **AzrielClasses/__BATCH_FILES/** - Developer productivity scripts (see below)

### TILIM/
Windows Forms application (.NET) for displaying Hebrew Psalms text, with a PHP backend in `php/` and `php-tilim/` subdirectories.

**Build:** Open `TILIM/TILIM.sln` in Visual Studio.

### Root Python/JS utilities
- `functions.py` - Excel utilities: `xlsx2csv()` (requires openpyxl), `excelColToNum()`
- `fix-chabad.js` - Audio resource extraction from Chabad website

## Batch Files & Scripts (AzrielClasses/__BATCH_FILES/)

Python utility module `azriel.py` provides common helpers used by other scripts:
- `run_os_command(cmd)` - Execute command with colored output
- `get_output_of_os_command(cmd)` - Capture command output
- `ask_yn(question)` - Interactive yes/no prompt

Key scripts:
- `git-pull-all.py` - Pull all branches, interactive push/merge prompts (requires GitPython)
- `pip_update_all.py` - Update all outdated pip packages
- `callwith.bat` / `call_with.py` - Execute commands with backtick substitution (like shell `$(...)`)
- `git-create-branch.py` - Git branch creation automation

## Language-Specific Notes

### C# (AzrielClasses)
- Extension methods pattern used extensively (e.g., `nullToEmptyString()`, `IsValidEmailAddress()`)
- Namespace: `AzrielClasses`

### C++ (AzrielClasses/CPP)
- Header-only implementations for data structures
- Uses STL containers (`std::map`, `std::priority_queue`, `std::vector`)

### Python
- Scripts expect `azriel.py` module in same directory
- Some scripts require external packages: `openpyxl`, `GitPython`
