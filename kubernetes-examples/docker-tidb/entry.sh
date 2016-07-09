#!/bin/bash

# ref -  http://f.0xffff.me/f/cluster
# ./bin/tidb-server -L=debug --store=tikv --path="$HOST:2379/pd?cluster=1" -lease 1 -P 500$id --status=1008$id> tidb$id.log 2>&1 &

/tidb-server
