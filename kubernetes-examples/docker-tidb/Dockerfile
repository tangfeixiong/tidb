FROM golang:1.6

ADD tidb-server entry.sh /

VOLUME ["/tmp/tidb"]

EXPOSE 4000 10080

CMD ["/entry.sh"]
