@for /f "delims=" %%i in ('@python %~dp0\call_with.py %*') do %%i
