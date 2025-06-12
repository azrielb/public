import os
import subprocess


cmd = 'python -m pip --disable-pip-version-check list --outdated'
print(cmd)
output = subprocess.check_output(cmd.split()).decode()
print(output)
'''An example for the output:
Package    Version Latest       Type
---------- ------- ------------ -----
pyelftools 0.29    0.30         wheel
starlette  0.31.1  0.33.0       wheel
uvicorn    0.23.2  0.24.0.post1 wheel
'''
lines = output.split('\n')[2:-1]
packagesThatNeedToBeUpdatedAlways = 'pip'
packagesNames = [packageInfo.split()[0] for packageInfo in lines]
if packagesNames:
    cmd = 'python -m pip install --no-warn-script-location --upgrade ' + packagesThatNeedToBeUpdatedAlways + ' ' + ' '.join(packagesNames)
    print(cmd)
    os.system(cmd)
else:
    print("All relevant pip packages are up-to-date")