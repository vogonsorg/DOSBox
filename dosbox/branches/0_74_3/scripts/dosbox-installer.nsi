!define VER_MAYOR 0
!define VER_MINOR 74
!define APP_NAME "DOSBox ${VER_MAYOR}.${VER_MINOR}-3 Installer"
!define COMP_NAME "DOSBox Team"
!define COPYRIGHT "Copyright ? 2002-2019 DOSBox Team"
!define DESCRIPTION "DOSBox Installer"

VIProductVersion "${VER_MAYOR}.${VER_MINOR}.3.0"
VIAddVersionKey  "ProductName"  "${APP_NAME}"
VIAddVersionKey  "CompanyName"  "${COMP_NAME}"
VIAddVersionKey  "FileDescription"  "${DESCRIPTION}"
VIAddVersionKey  "FileVersion"  "${VER_MAYOR}.${VER_MINOR}.3.0"
VIAddVersionKey  "ProductVersion"  "${VER_MAYOR}, ${VER_MINOR}, 3, 0"
VIAddVersionKey  "LegalCopyright"  "${COPYRIGHT}"

; The name of the installer
Name "${APP_NAME}"

; The file to write
OutFile "DOSBox${VER_MAYOR}.${VER_MINOR}-3-win32-installer.exe"

; The default installation directory
InstallDir "$PROGRAMFILES\DOSBox-${VER_MAYOR}.${VER_MINOR}-3"

; The text to prompt the user to enter a directory
DirText "This will install DOSBox v${VER_MAYOR}.${VER_MINOR}-3 on your computer. Choose a directory"
SetCompressor /solid lzma


LicenseData COPYING
LicenseText "DOSBox v${VER_MAYOR}.${VER_MINOR}-3 License" "Next >"

; Else vista enables compatibility mode
RequestExecutionLevel admin
; Shortcuts in all users

ComponentText "Select components for DOSBox"
; The stuff to install
Section "!Core files" Core
SetShellVarContext all

  ; Set output path to the installation directory.
  ClearErrors
  SetOutPath $INSTDIR
  IfErrors error_createdir
  SectionIn RO

  ; Put file there
  
  CreateDirectory "$INSTDIR\Video Codec"
  CreateDirectory "$INSTDIR\Documentation"
  SetOutPath "$INSTDIR\Documentation"
  File /oname=README.txt README
  File /oname=COPYING.txt COPYING
  File /oname=THANKS.txt THANKS
  File /oname=NEWS.txt NEWS
  File /oname=AUTHORS.txt AUTHORS
  File /oname=INSTALL.txt INSTALL
  SetOutPath "$INSTDIR"

  File "/oname=DOSBox ${VER_MAYOR}.${VER_MINOR}-3 Manual.txt" README
  File "/oname=DOSBox.exe" DOSBox.exe
  File SDL.dll
  File SDL_net.dll
  File "/oname=Video Codec\zmbv.dll" zmbv.dll
  File "/oname=Video Codec\zmbv.inf" zmbv.inf
  File "/oname=Video Codec\Video Instructions.txt" README.video
  File "/oname=DOSBox ${VER_MAYOR}.${VER_MINOR}-3 Options.bat" editconf.bat
  File "/oname=Reset KeyMapper.bat" resetmapper.bat
  File "/oname=Reset Options.bat" resetconf.bat
  File "/oname=Screenshots & Recordings.bat" captures.bat
  
  CreateDirectory "$SMPROGRAMS\DOSBox-${VER_MAYOR}.${VER_MINOR}-3"
  CreateDirectory "$SMPROGRAMS\DOSBox-${VER_MAYOR}.${VER_MINOR}-3\Extras"
  CreateDirectory "$SMPROGRAMS\DOSBox-${VER_MAYOR}.${VER_MINOR}-3\Extras\Video"
  CreateDirectory "$SMPROGRAMS\DOSBox-${VER_MAYOR}.${VER_MINOR}-3\Options"
  CreateShortCut "$SMPROGRAMS\DOSBox-${VER_MAYOR}.${VER_MINOR}-3\DOSBox ${VER_MAYOR}.${VER_MINOR}-3.lnk" "$INSTDIR\DOSBox.exe" "-userconf" "$INSTDIR\DOSBox.exe" 0
  CreateShortCut "$SMPROGRAMS\DOSBox-${VER_MAYOR}.${VER_MINOR}-3\DOSBox ${VER_MAYOR}.${VER_MINOR}-3 Manual.lnk" "$INSTDIR\Documentation\README.txt"
  CreateShortCut "$SMPROGRAMS\DOSBox-${VER_MAYOR}.${VER_MINOR}-3\Extras\DOSBox ${VER_MAYOR}.${VER_MINOR}-3 (noconsole).lnk" "$INSTDIR\DOSBox.exe" "-noconsole -userconf" "$INSTDIR\DOSBox.exe" 0
  CreateShortCut "$SMPROGRAMS\DOSBox-${VER_MAYOR}.${VER_MINOR}-3\Extras\Uninstall.lnk" "$INSTDIR\uninstall.exe" "" "$INSTDIR\uninstall.exe" 0
  CreateShortCut "$SMPROGRAMS\DOSBox-${VER_MAYOR}.${VER_MINOR}-3\Extras\Screenshots & Recordings.lnk" "$INSTDIR\DOSBox.exe" "-opencaptures explorer.exe"

  CreateShortCut "$SMPROGRAMS\DOSBox-${VER_MAYOR}.${VER_MINOR}-3\Options\DOSBox ${VER_MAYOR}.${VER_MINOR}-3 Options.lnk" "$INSTDIR\DOSBox.exe" "-editconf notepad.exe -editconf $\"%SystemRoot%\system32\notepad.exe$\" -editconf $\"%WINDIR%\notepad.exe$\""
  CreateShortCut "$SMPROGRAMS\DOSBox-${VER_MAYOR}.${VER_MINOR}-3\Options\Reset Options.lnk" "$INSTDIR\DOSBox.exe" "-eraseconf"
  CreateShortCut "$SMPROGRAMS\DOSBox-${VER_MAYOR}.${VER_MINOR}-3\Options\Reset KeyMapper.lnk" "$INSTDIR\DOSBox.exe" "-erasemapper"

  CreateShortCut "$SMPROGRAMS\DOSBox-${VER_MAYOR}.${VER_MINOR}-3\Extras\Video\Video instructions.lnk" "$INSTDIR\Video Codec\Video Instructions.txt"
;change outpath so the working directory gets set to zmbv
SetOutPath "$INSTDIR\Video Codec"
  ; Shortcut creation depends on wether we are 9x of NT
  ClearErrors
  ReadRegStr $R0 HKLM "SOFTWARE\Microsoft\Windows NT\CurrentVersion" CurrentVersion
  IfErrors we_9x we_nt
we_nt:
  ;shortcut for win NT
  CreateShortCut "$SMPROGRAMS\DOSBox-${VER_MAYOR}.${VER_MINOR}-3\Extras\Video\Install movie codec.lnk" "rundll32" "setupapi,InstallHinfSection DefaultInstall 128 $INSTDIR\Video Codec\zmbv.inf"
  goto end
we_9x:
  ;shortcut for we_9x
  CreateShortCut "$SMPROGRAMS\DOSBox-${VER_MAYOR}.${VER_MINOR}-3\Extras\Video\Install movie codec.lnk" "rundll" "setupx.dll,InstallHinfSection DefaultInstall 128 $INSTDIR\Video Codec\zmbv.inf"
end:
SetOutPath $INSTDIR
WriteUninstaller "uninstall.exe"

  goto end_section

error_createdir:
  MessageBox MB_OK "Can't create DOSBox program directory, aborting."
  Abort
  goto end_section

end_section:
SectionEnd ; end the section

Section "Desktop Shortcut" SecDesktop
SetShellVarContext all

CreateShortCut "$DESKTOP\DOSBox ${VER_MAYOR}.${VER_MINOR}-3.lnk" "$INSTDIR\DOSBox.exe" "-userconf" "$INSTDIR\DOSBox.exe" 0

SectionEnd ; end the section 


UninstallText "This will uninstall DOSBox  v${VER_MAYOR}.${VER_MINOR}-3. Hit next to continue."

Section "Uninstall"

; Shortcuts in all users
SetShellVarContext all

  Delete "$DESKTOP\DOSBox ${VER_MAYOR}.${VER_MINOR}-3.lnk"
  ; remove registry keys
  ; remove files
  Delete $INSTDIR\Documentation\README.txt
  Delete $INSTDIR\Documentation\COPYING.txt
  Delete $INSTDIR\Documentation\THANKS.txt
  Delete $INSTDIR\Documentation\NEWS.txt
  Delete $INSTDIR\Documentation\AUTHORS.txt
  Delete $INSTDIR\Documentation\INSTALL.txt
  Delete "$INSTDIR\DOSBox ${VER_MAYOR}.${VER_MINOR}-3 Manual.txt"
  Delete "$INSTDIR\DOSBox.exe"
  Delete $INSTDIR\SDL.dll
  Delete $INSTDIR\SDL_net.dll
  Delete "$INSTDIR\Video Codec\zmbv.dll"
  Delete "$INSTDIR\Video Codec\zmbv.inf"
  Delete "$INSTDIR\Video Codec\Video Instructions.txt"
  ;Files left by sdl taking over the console
  Delete $INSTDIR\stdout.txt
  Delete $INSTDIR\stderr.txt
  Delete "$INSTDIR\DOSBox ${VER_MAYOR}.${VER_MINOR}-3 Options.bat"
  Delete "$INSTDIR\Reset KeyMapper.bat"
  Delete "$INSTDIR\Reset Options.bat"
  Delete "$INSTDIR\Screenshots & Recordings.bat"

  ; MUST REMOVE UNINSTALLER, too
  Delete $INSTDIR\uninstall.exe


  Delete "$SMPROGRAMS\DOSBox-${VER_MAYOR}.${VER_MINOR}-3\DOSBox ${VER_MAYOR}.${VER_MINOR}-3.lnk"
  Delete "$SMPROGRAMS\DOSBox-${VER_MAYOR}.${VER_MINOR}-3\DOSBox ${VER_MAYOR}.${VER_MINOR}-3 Manual.lnk"
  Delete "$SMPROGRAMS\DOSBox-${VER_MAYOR}.${VER_MINOR}-3\Extras\DOSBox ${VER_MAYOR}.${VER_MINOR}-3 (noconsole).lnk"
  Delete "$SMPROGRAMS\DOSBox-${VER_MAYOR}.${VER_MINOR}-3\Extras\Uninstall.lnk"
  Delete "$SMPROGRAMS\DOSBox-${VER_MAYOR}.${VER_MINOR}-3\Extras\Screenshots & Recordings.lnk"

  Delete "$SMPROGRAMS\DOSBox-${VER_MAYOR}.${VER_MINOR}-3\Options\DOSBox ${VER_MAYOR}.${VER_MINOR}-3 Options.lnk"
  Delete "$SMPROGRAMS\DOSBox-${VER_MAYOR}.${VER_MINOR}-3\Options\Reset Options.lnk"
  Delete "$SMPROGRAMS\DOSBox-${VER_MAYOR}.${VER_MINOR}-3\Options\Reset KeyMapper.lnk"

  Delete "$SMPROGRAMS\DOSBox-${VER_MAYOR}.${VER_MINOR}-3\Extras\Video\Video instructions.lnk"
  Delete "$SMPROGRAMS\DOSBox-${VER_MAYOR}.${VER_MINOR}-3\Extras\Video\Install movie codec.lnk"



; remove shortcuts, if any.
; remove directories used.
  RMDir "$INSTDIR\Documentation"
  RMDir "$INSTDIR\Video Codec"
  RMDir "$INSTDIR"
  RMDir "$SMPROGRAMS\DOSBox-${VER_MAYOR}.${VER_MINOR}-3\Options"
  RMDir "$SMPROGRAMS\DOSBox-${VER_MAYOR}.${VER_MINOR}-3\Extras\Video"
  RMDir "$SMPROGRAMS\DOSBox-${VER_MAYOR}.${VER_MINOR}-3\Extras"
  RMDir "$SMPROGRAMS\DOSBox-${VER_MAYOR}.${VER_MINOR}-3"
SectionEnd

; eof
