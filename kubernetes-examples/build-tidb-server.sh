#!/bin/bash -e

pushd ..

make parser

LDFLAGS+=" -X github.com/pingcap/tidb/util/printer.TiDBBuildTS=$(date -u '+%Y-%m-%d %I:%M:%S')"
LDFLAGS+=" -X github.com/pingcap/tidb/util/printer.TiDBGitHash=$(git rev-parse HEAD)"

rm -rf vendor && ln -s _vendor/vendor vendor
cd tidb-server && CGO_ENABLED=0 go build  -installsuffix cgo -o ../kubernetes-examples/docker-tidb/tidb-server -v -a
rm -rf vendor

popd

