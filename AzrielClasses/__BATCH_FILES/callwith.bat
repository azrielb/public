@if "%1"=="" (
	@python %~dp0\call_with.py
) else (
	@for /f "delims=" %%i in ('@python %~dp0\call_with.py %*') do %%i
)