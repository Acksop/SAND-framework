<div align="center" width="100%">
<br/>
 <img src="https://www.phplist.com/site/images/readme-images/logo-icon.svg" align="center" style="width:220px;">
</div>

#
[![Build Status](https://travis-ci.org/phpList/phplist3.svg?branch=master)](https://travis-ci.org/phpList/phplist3)
[![Stable release](https://img.shields.io/badge/stable-3.5.2-blue.svg)](https://sourceforge.net/projects/phplist/files/phplist/)
[![License](https://poser.pugx.org/phplist/phplist4-core/license.svg)](https://www.gnu.org/licenses/agpl-3.0.en.html)
<a href="http://translate.phplist.org/engage/phplist/?utm_source=widget">
<img src="http://translate.phplist.org/widgets/phplist/-/svg-badge.svg" alt="Translation status" />
</a>
<br/>
Fully functional Open Source email marketing manager for creating, sending, integrating, and analysing email campaigns and newsletters: [https://www.phplist.org](https://www.phplist.org)

phpList includes analytics, segmentation, content personalisation, bounce processing, plugin-based architecture, and multiple APIs. Used in 95 countries, available in 20 languages, and used to send more than 25 billion campaign messages in 2018.

Deploy it on your own server, or get a hosted account at http://phplist.com.

<div align="center" width="100%">
  <a href="http://phplist.org/">Community</a> |
  <a href="http://phplist.com/">Hosted</a> |
  <a href="https://demo.phplist.org">Demo</a>
  <br>
  <img src="https://www.phplist.com/site/images/readme-images/panel.png" align="center" width="100%">
  <br/>
</div>

---

<div align="center" width="100%">
  <img src="https://www.phplist.com/site/images/readme-images/features_icon.svg" align="center" width="96px" />
  <h3>Features</h3>
</div>

* Responsive web-based, and command-line interfaces
* Real-time analytics: track message responses and subscriber behaviour
* Message-queue management: load-balances and throttles multiple accounts and campaigns; tracks every delivery outcome
* Content personalisation: every message customised to individual subscriber attributes and preferences
* Automated bounce management and processing: policy and regex-based handling with every bounce accessibly archived
* Schedule, pause, resume, repeat, and requeue campaigns
* Amazon SES: built-in optimised support
* CSV and Excel based subscriber import and export, including attributes and preferences
* Send a Webpage: automatic remote html polling, conversion, and dispatch
* Email attachments support
* Domain-based throttling: comply with host-specific policies by defining custom rules
* RSS Feeds: phpList can be set up to read a range of RSS sources and send the contents on a regular basis to users. The user can identify how often they want to receive the feeds.

---

<div align="center">
<img src="https://www.phplist.com/site/images/readme-images/get_phpList_icon.svg" align="center" width="96px" />
 <h3>Get phpList</h3>
</div>

If you'd like to use phpList for your own campaigns, or you just want to try phpList out, there is no need to do all the work of installing it yourself. [phpList Hosted](https://phplist.com) is free to use, just [sign up](https://phplist.com/register) to get started.

If at a later time you do want to migrate from your own installation to phpList Hosted, or vice versa, your data can be migrated.

Alternatively, you can try out the latest phpList release at [phplist.org](https://demo.phplist.org/lists/admin/). This installation is wiped and refreshed every hour.



<div align="center" style="max-width: 100%;">
      <img src="https://www.phplist.com/site/images/readme-images/icon_1.svg" style="   display: inline-block;max-width: 96px;height: auto;width: 30%;margin: 1%;"/>
            <img src="https://www.phplist.com/site/images/readme-images/icon_2.svg" style="display: inline-block;max-width: 96px;height: auto;width: 30%;margin: 1%;    margin-left: 100px; margin-right: 100px;"/>
            <img src="https://www.phplist.com/site/images/readme-images/icon_3.svg" style=" display: inline-block;max-width: 96px;height: auto;width: 30%;margin: 1%;"/>
</div>

<div align="center" width="100%">
<a href="#" style="text-decoration:none;font-size:22px;color:#000;">Requirements</a>
<a href="#" style="margin-right:90px;margin-left:60px; text-decoration:none;font-size:22px;color:#000;">Development</a>
<a href="#" style="text-decoration:none;font-size:22px;color:#000;">Installation</a>
</div>



---

<div align="center" width="100%">
  <img src="https://www.phplist.com/site/images/readme-images/upgrade_icon.svg" align="center" width="96px" />
  <h3>Upgrade</h3>
</div>

#### For users

See [Upgrading a manual installation](https://www.phplist.org/manual/ch031_upgrading.xhtml)

#### For developers

'Use translate.phplist.org to translate the phpList admin interface.'
How to upgrade from any previous version to the latest version


How to upgrade from any previous version to the latest version

<div><img src="https://www.phplist.com/site/images/readme-images/1.svg" width="20px" style="margin-top:5px;"/> <strong>BACKUP your database</strong> (e.g. `mysqldump -u[user] -p[password] [database] > phplist-backup.sql`)</div><br/>

<div><img src="https://www.phplist.com/site/images/readme-images/2.svg" width="20px"/> <strong>Copy your old configured files to some safe place</strong></div>

These files are:
* `lists/config/config.php`
* possibly `lists/texts/english.inc` or any other `language.inc` if you have edited it

<div><img src="https://www.phplist.com/site/images/readme-images/3.svg" width="20px"/> <strong>Copy the files from the tar file to your webroot.</strong></div>

You can copy everything in the `lists` directory in the tar file to your website. To facilitate future upgrades, ie to make it easier for you to simply copy everything I have now put the "configurable" files in their own directory. They reside in "lists/config". This is hopefully going to be the directory that you can keep between upgrades, and that will contain the only information that you want to be changed in order to make it work for your own site.

<img src="https://www.phplist.com/site/images/readme-images/4.svg" width="20px"/> <strong>Copy your configuration files to lists/config or re-edit the new config file sometimes new features are added to the config file, so it's better to use the new config file and re-adapt it to your situation.</strong>

An example .htaccess exists file in this directory. You should not allow access to this directory from the webserver at all. The example will work with apache.

You can overwrite the files that are there. They are example files.

<div><img src="https://www.phplist.com/site/images/readme-images/5.svg" width="20px"/> Go to `http://yourdomain/lists/admin/` and choose the 'Upgrade' link</div> <br/>

<img src="https://www.phplist.com/site/images/readme-images/6.svg" width="20px"/><strong> Click the link on this page.</strong><br/>
This process may take quite a while if your database is large. Don't interrupt it.

#

<div align="center" width="100%">
  <img src="https://www.phplist.com/site/images/readme-images/issues_icon.svg" align="center" width="96px" />
  <h3>Issues</h3>
  <p>Report issues to <a href="https://mantis.phplist.org/bug_report_page.php">Mantis issue tracker</a> (select project phpList 3 application)<br>
     'Please don't use GitHub issues'.</p>
</div>

#

<div align="center" width="100%">
  <img src="https://www.phplist.com/site/images/readme-images/languages_icon.svg" align="center" width="96px" />
  <h3>Languages, Info files and Contextual help</h3>
</div>

In the directory `phplists/lists/texts` you will find existing translations of the public pages of phpList. You can use them in your config file to make the frontend of the system appear in the language of your choice.

In the config file there are a lot of choices to make about your particular installation. Make sure to read it carefully and step by step work your way through it. A lot of information is provided with each step.

#

<div align="center" width="100%">
  <img src="https://www.phplist.com/site/images/readme-images/theme-icon.png" align="center" width="96px" />
  <h3>Themes</h3>
</div>

phpList comes with [Trevelin](https://github.com/phpList/phplist-ui-bootlist) theme on default. Older versions than 3.4.6, include additionally [Dressprow](https://github.com/phpList/phplist-ui-dressprow) which is not currently maintained. Patches to Dressprow theme are still welcomed. Both themes are available for installation via [GitHub](https://github.com/phpList).

To get the latest copy of each theme you should clone them individually into public_html/lists/admin/ui/
For the prerequisites of each and Installation guide on your development copy make sure you check the README files of the repos.

- [Trevelin](https://github.com/phpList/phplist-ui-bootlist)
- [Dressprow](https://github.com/phpList/phplist-ui-dressprow)

#

<div align="center" width="100%">
  <img src="https://www.phplist.com/site/images/readme-images/code_of_conduct_icon.svg" align="center" width="96px" />
  <h3>Code of Conduct</h3>
</div>

This project adheres to a [Contributor Code of Conduct](CODE_OF_CONDUCT.md). By participating in this project and its community, you are expected to uphold this code.

#

<div align="center" width="100%">
  <img src="https://www.phplist.com/site/images/readme-images/license_icon.svg" align="center" width="96px" />
  <h3>License</h3>
</div>

phpList 3 is licensed under the terms of the AGPLv3 Open Source license and is available free of charge.
