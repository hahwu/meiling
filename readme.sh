#!/bin/bash
E_WRONG_ARGS=65
ARGS=1
if [[ $# -ne "$ARGS" ]]; then
  #statements
  echo "Usage:`basename $0` need one args"
fi
