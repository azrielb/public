import os
import subprocess
import sys


if os.name == 'nt':
    os.system('') # thanks to ChatGPT that has given me this tip for using the coloring in `run_os_command`


def print_command(cmd):
    print(f'\033[93m{cmd}\033[0m')

def run_os_command(cmd, abort_for_any_error=False):
    print_command(cmd)
    exit_code = os.system(cmd)
    print()
    if abort_for_any_error and exit_code != 0:
        print(f"Error #{exit_code} has been occured!")
        sys.exit(exit_code)
    return exit_code

def get_output_of_os_command(cmd, print_cmd=False):
    if print_cmd:
        print_command(cmd)
    try:
        if os.name == 'nt':
            cmd = f'chcp 65001 >nul && {cmd}'
        output = subprocess.check_output(cmd, shell=True, text=True, encoding='utf-8').strip()
        if print_cmd:
            print(output)
            print()
        return output
    except subprocess.CalledProcessError as e:
        print(f"Error running command `{cmd}`:", e)
        raise(e)

def ask_yn(question, yes_is_default = True):
    options = '[Y]|[n]' if yes_is_default else '[y]|[N]'
    default_res = 'y' if yes_is_default else 'n'
    res = (input(f"{question} {options} ") or default_res)[0].lower()
    print()
    if yes_is_default:
        return res != 'n'
    return res == 'y'
    
if __name__ == "__main__":
    while ask_yn(""):
        pass
