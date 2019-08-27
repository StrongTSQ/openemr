# OpenEMR

[OpenEMR](http://open-emr.org) is the most popular open source electronic health records and medical practice management solution. [ONC certified](http://open-emr.org/wiki/index.php/OpenEMR_Wiki_Home_Page#ONC_Ambulatory_EHR_Certification) with international usage, OpenEMR's goal is a superior alternative to its proprietary counterparts.

# EASIPRO
This fork of OpenEMR is part of the work from study EASIPRO -- The Electronic Health Record Access to Seamless Integration of PROMIS. EASIPRO grant team includes 9 collaborating Clinical and Translational Science Award (CTSA) institutions from across the United States. We have expertise in EHR integration, SMART, FHIR, and patient-reported outcomes measurement science. In this fork, we create a PRO module allowing OpenEMR to be able to work with patient reported outcomes. 
# PROs
Patient reported outcomes (PROs) are increasingly recognized as valuable and essential information for achieving health system goals. Information from the patient's perspective is essential to supporting a patient-centred approach to care. It is also essential to understanding whether health care services and procedures make a difference to patients' health status and quality of life. Learn More >> http://www.healthmeasures.net 
# Installation
### Step 1. Download
git clone git@github.com:StrongTSQ/openemr.git
### Step 2. Move openemr folder to your webserver root directory
Different OS may have different webserver root directory. For example, in CentOS Linux, it is /var/www/html/ and it is /Library/WebServer/Documents/.
### Step 3. Install Composer and NPM
Follow instructions from https://www.open-emr.org/wiki/index.php/Composer_and_NPM
### Step 4. Build OpenEMR
cd openemr

composer install

npm install

npm run build

composer dump-autoload -o
### Step 5. Configure Database and Admin Account
Open http://localhost/openemr in your browser and follow the OpenEMR guide to finish the configurations.

### License

[GNU GPL](LICENSE)
