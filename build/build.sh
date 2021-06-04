#!/bin/bash

# PHP METRICS
./bin/phpmetrics --report-html=./data/phpmetrics ../ --exclude="vendor","build","tests","data","console/skel","application/modules","application/include/vues/cache"

# PHP DOCUMENTOR
php ./bin/phpDocumentor.phar -d ../application -d ../console -d ../domain --ignore "vendor/*,build/*,data/*,tests/*,console/skel/*,application/modules/*,application/inculde/vues/cache/*" -t ./data/api-docs/

# PHP MESS DETECTOR
php ./bin/phpmd.phar ../ html codesize,design,naming,unusedcode --exclude '*vendor*' --exclude '*tests*' --exclude '*build*' --exclude '*data*' --exclude '*skel*' --exclude '*modules*' --exclude '*cache*' > ./data/phpmd.html

# CHARTS OF PROJECTS
./bin/pdepend --jdepend-chart=data/jdepend-chart.svg --overview-pyramid=data/jdepend-overview.svg --summary-xml=data/jdepend-summary.xml --ignore=vendor,tests,build,data,console/skel,application/modules,application/inculde/vues/cache ../

# TEXT METRIC OF PROJECT
php ./bin/phploc-7.0.2.phar ../ --exclude ../vendor --exclude ../build --exclude ../tests --exclude ../data --exclude ../console/skel --exclude ../application/modules --exclude ../application/include/vues/cache > data/phploc.txt

# DUPLICATED LINES OF PROJECT
php ./bin/phpcpd-6.0.3.phar --exclude ../vendor --exclude ../build --exclude ../tests --exclude ../data --exclude ../console/skel --exclude ../application/modules --exclude ../application/include/vues/cache ../ > data/phpcpd.txt

# DEAD CODE DETECTOR
php ./bin/phpdcd-1.0.2.phar --exclude="../vendor" --exclude="../build" --exclude="../tests" --exclude="../data" --exclude="../console/skel" --exclude="../application/modules" --exclude="../application/include/vues/cache" --recursive ../ > data/phpdcd.txt

# PHP DOX
php ./bin/phpdox-0.12.0.phar