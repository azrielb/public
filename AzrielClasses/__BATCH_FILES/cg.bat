@echo off
setlocal EnableDelayedExpansion

if "%~1"=="" (
    echo Usage: %~nx0 ^<folder_name^>
    exit /b 1
)

set "SEARCH=%~1"
set "DIR=%~dp0"

:loop
if exist "%DIR%%SEARCH%\" (
    endlocal
    cd /d "%DIR%%SEARCH%"
    exit /b 0
)

:: Check if we're at the drive root
set "PARENT="
for %%I in ("%DIR%..") do set "PARENT=%%~fI\"

if "%DIR%"=="%PARENT%" (
    endlocal
    exit /b 1
)

set "DIR=%PARENT%"
goto loop
