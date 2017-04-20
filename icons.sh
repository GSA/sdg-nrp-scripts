#!/usr/local/bin/bash
declare -A languages=( ["en"]="English" ["es"]="Spanish" ["fr"]="French" ["ar"]="Arabic" ["zh"]="Chinese" ["ru"]="Russian")
for lang in "${!languages[@]}"
do

declare language=${languages[$lang]}
declare initial=${language:0:1}

wget "http://www.un.org/en/images/sustainabledevelopment/$language/Non-UN%20Entities/WEB%20FILES.zip"
unzip "WEB FILES.zip"
mv "WEB FILES/$initial Icons_WEB/Square_RGB" "$lang"
cd $lang
ls | sed -n "s/\(\(.*-\)\([0-9]\{2\}\)*\(\.png\)\)/mv \"\1\" ${lang}-sdg-goal-\3\4/p" | sh
cd ..
rm -r "WEB FILES" "__MACOSX" "WEB FILES.zip"

done
exit 0
