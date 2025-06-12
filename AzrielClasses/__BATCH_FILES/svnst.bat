svn st %USERPROFILE%\Documents\SVN_folders\* 2>&1 |grep -E ".." |grep -vE "(xternal|^ *[X]|Release\\\\(makefile|[^.]*.mk)|\\.cproject)"
