import os
import sys


def run_os_command(cmd, abort_for_any_error=False):
    print(f'\033[93m{cmd}\033[0m')
    exit_code = os.system(cmd)
    print()
    if abort_for_any_error and exit_code != 0:
        print(f"Error #{exit_code} has been occured!")
        sys.exit(exit_code)
    return exit_code


if len(sys.argv) != 3:
    sys.exit("The input should contain only the path and the branch name (each in one word)!")
path = sys.argv[1]
branch_name = sys.argv[2]

os.chdir(path)
run_os_command("git status", True)

res = input(f"Do you want to create (locally and remotely) this branch [{branch_name}]? [y]|[N] ").lower()
print()
if res != 'y':
    sys.exit()

run_os_command("git checkout main", True)
run_os_command("git pull", True)
run_os_command(f"git checkout -b {branch_name}", True)
run_os_command(f"git push --set-upstream origin {branch_name}", True)
run_os_command("git status")
