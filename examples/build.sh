#!/bin/bash

[[ "$1" == "" ]] && {
    echo "Usage: ./build.sh x.php"
    exit
}

SRC=$1
FILE=`basename $SRC`

phptobpc $SRC > $FILE

bpc -v \
    -c ../lib/bpc.conf \
    -u react-promise \
    -u amp \
    -d display_errors=on \
    -d max_excution_time=-1 \
    -d suppress_runtime_too_many_arguments_warning=1 \
    $FILE

rm $FILE
