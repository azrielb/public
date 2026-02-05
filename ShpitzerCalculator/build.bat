@echo off
echo Building Shpitzer Calculator...

REM Try to find csc.exe (C# compiler) in common locations
set CSC=
if exist "%WINDIR%\Microsoft.NET\Framework\v4.0.30319\csc.exe" (
    set CSC=%WINDIR%\Microsoft.NET\Framework\v4.0.30319\csc.exe
) else if exist "%WINDIR%\Microsoft.NET\Framework64\v4.0.30319\csc.exe" (
    set CSC=%WINDIR%\Microsoft.NET\Framework64\v4.0.30319\csc.exe
)

if "%CSC%"=="" (
    echo ERROR: Could not find C# compiler. Please ensure .NET Framework 4.0 is installed.
    pause
    exit /b 1
)

echo Using compiler: %CSC%
echo.

"%CSC%" /target:winexe /out:ShpitzerCalculator.exe ShpitzerCalculator.cs

if %ERRORLEVEL%==0 (
    echo.
    echo Build successful! Created: ShpitzerCalculator.exe
    echo You can now run ShpitzerCalculator.exe
) else (
    echo.
    echo Build failed with error code %ERRORLEVEL%
)

pause
