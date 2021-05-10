# PHP Uploader
A PHP uploader with Discord embed and Twitter card support.  
PHP Uploader was originaly called sharex-uploader and this is a refactor of that with nicer code, comments and upload logging.  

### How To Install
This guide is a basic guide, refer to the wiki for a more detailed guide.
- First, download a release or the code as a zip file.
- Extract the download.
- Configure the config.
- Make sure `protected/uploads.json` has permissions `666`.
- Make sure `uploads/` has permissions `777`.
- If you havent already, enable htaccess in your apache or nginx config.
- Finally, upload all the files onto your server if you havent already.  

### Sharex Guide
Now that you have installed the uploader onto your server you will want a way to upload the files.  
The best way is to use ShareX, you can download this [here](https://getsharex.com/).  
- First, download the example sharex config from the code.
- Make sure you have configured the sharex config's password to be the same as the password in the config.
- Finally, add the sharex config into sharex and start uploading!

### To Come
Check the [projects](https://github.com/ilyBenny/php-uploader/projects/1) for progress.
- Accounts
- Dashboard
