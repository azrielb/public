import git # requires "GitPython"
import os


def run_os_command(cmd):
    print(f'\033[93m{cmd}\033[0m')
    exit_code = os.system(cmd)
    print()
    return exit_code


try:
    repo = git.repo.Repo('.', search_parent_directories=True)
except Exception as e:
    print(type(e),e)
    repo = git.repo.Repo(fr"{os.path.dirname(__file__)}\..\..\.git")
    
os.chdir(repo.git.rev_parse("--show-toplevel"))
print()
run_os_command("git pull")
for branch in repo.branches:
    if branch != repo.active_branch:
        exit_code = run_os_command(f"git fetch origin {branch.name}:{branch.name}") #we use the command line interface for printing the information
        if exit_code != 0:
            res = input("Do you want to delete this local branch? [y]|[N] ").lower()
            print()
            if res == 'y':
                run_os_command("git branch -D " + branch.name)            
run_os_command("git status")
if 'use "git push"' in repo.git.status():
    res = input("Do you want to push your changes? [Y]|[n] ").lower()
    print()
    if res != 'n':
        run_os_command("git push")
