@chcp 65001 >nul
@if [%1]==[] (
	@python %~dp0\call_with.py
) else (
	@for /f "delims=" %%i in ('@python -X utf8 %~dp0\call_with.py %*') do %%i
)