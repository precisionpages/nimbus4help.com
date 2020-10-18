<?PHP

/*

Simfatic Forms Main Form processor script



This script does all the server side processing. 

(Displaying the form, processing form submissions,

displaying errors, making CAPTCHA image, and so on.) 



All pages (including the form page) are displayed using 

templates in the 'templ' sub folder. 



The overall structure is that of a list of modules. Depending on the 

arguments (POST/GET) passed to the script, the modules process in sequence. 



Please note that just appending  a header and footer to this script won't work.

To embed the form, use the 'Copy & Paste' code in the 'Take the Code' page. 

To extend the functionality, see 'Extension Modules' in the help.



*/



@ini_set("display_errors", 1);//the error handler is added later in FormProc

error_reporting(E_ALL & ~((defined('E_STRICT')?E_STRICT:0)|E_NOTICE));



require_once(dirname(__FILE__)."/includes/ContactUs-lib.php");

$formproc_obj =  new SFM_FormProcessor('ContactUs');

$formproc_obj->initTimeZone('US/Mountain');

$formproc_obj->setFormID('fe537b68-7f65-4377-8d10-58ad1e03aa97');

$formproc_obj->setFormKey('a0d00a12-d9da-439a-a534-5a3afdc7552e');

$formproc_obj->setLocale('en-US','yyyy-MM-dd');

$formproc_obj->setEmailFormatHTML(true);

$formproc_obj->EnableLogging(false);

$formproc_obj->SetDebugMode(false);

$formproc_obj->setIsInstalled(true);

$formproc_obj->SetPrintPreviewPage(sfm_readfile(dirname(__FILE__)."/templ/ContactUs_print_preview_file.txt"));

$formproc_obj->SetSingleBoxErrorDisplay(true);

$formproc_obj->setFormPage(0,sfm_readfile(dirname(__FILE__)."/templ/ContactUs_form_page_0.txt"));

$formproc_obj->AddElementInfo('Name','text','');

$formproc_obj->AddElementInfo('Email','text','');

$formproc_obj->AddElementInfo('Multiline','multiline','');

$formproc_obj->SetHiddenInputTrapVarName('t29811876055ddde4af40');

$formproc_obj->SetFromAddress('info@nimbusqb.com');

$sfm_captcha =  new FM_CaptchaCreator('Captcha');

$sfm_captcha->SetSize(150,60);

$sfm_captcha->SetCharset('2356789ABCDEFGHJKLMNPQRSTUVWXYZ');

$sfm_captcha->SetCaseInsensitiveMatch(true);

$sfm_captcha->SetNChars(6);

$sfm_captcha->SetNLines(4);

$sfm_captcha->SetFontFile('images/SFOldRepublicBold.ttf');

$sfm_captcha->SetErrorStrings('Please input the code displayed in the image','The code does not match. Please try again.');

$formproc_obj->addModule($sfm_captcha);



$page_renderer =  new FM_FormPageDisplayModule();

$formproc_obj->addModule($page_renderer);



$validator =  new FM_FormValidator();

$validator->addValidation("Name","required","Please fill in Name");

$validator->addValidation("Email","required","Please fill in Email");

$validator->addValidation("Email","email","The input for Email should be a valid email value");

$validator->addValidation("Multiline","required","Please fill in Multiline");

$formproc_obj->addModule($validator);



$data_email_sender =  new FM_FormDataSender(sfm_readfile(dirname(__FILE__)."/templ/ContactUs_email_subj.txt"),sfm_readfile(dirname(__FILE__)."/templ/ContactUs_email_body.txt"),'%Email%');

/*$data_email_sender->AddToAddr('Admin <admin@precisionpages.com>');*/

$data_email_sender->AddToAddr('Marilyn <marilyn@nimbusqb.com>');

$formproc_obj->addModule($data_email_sender);



$tupage =  new FM_ThankYouPage(sfm_readfile(dirname(__FILE__)."/templ/ContactUs_thank_u.txt"));

$formproc_obj->addModule($tupage);



$sfm_captcha->SetValidator($validator);

$page_renderer->SetFormValidator($validator);

$formproc_obj->ProcessForm();



?>