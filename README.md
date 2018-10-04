# Big Picture - Version 1.2, modified
## Omeka Classic Theme

Modified for use by the Vermont Music Archive Website.

All audio files are displayed using the Omeka Theming Function file_display(). This permits the site to use the StreamOnly plugin to protect audio files from being downloaded.

The StreamOnly plugin uses the Omeka Classic theming functions to register a callback routine to display custom HTML containing the URL of a script and a single-use token. The script uses the token to deliver a single download of the protected audio file. Protected audio files are only accessible through execution of the callback, which is executed server-side. Building a URL based on the Omeka file directory structure, as is done in many older themes, will result in "File Not Found" errors for protected files.