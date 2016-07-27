FROM alpine:3.4
MAINTAINER tangfeixiong <fxtang@qingyuanos.com>

RUN apk add --update bash ca-certificates && rm -rf /var/cache/apk/*

ADD tidb-server entry.sh /

VOLUME ["/tmp/tidb"]

EXPOSE 4000 10080

CMD ["/entry.sh"]
