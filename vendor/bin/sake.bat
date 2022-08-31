@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../silverstripe/framework/sake
bash "%BIN_TARGET%" %*
