FULLVERSION ?= ${VERSION}

.PHONY: all build publish
all: build publish
build:
	cat docker/Dockerfile > Dockerfile
	docker build -t jaymoulin/yamete:pc-${VERSION} .
	cat docker/Dockerfile | sed "s/FROM alpine/FROM multiarch\/alpine:armhf-latest-stable/g" > Dockerfile
	docker build -t jaymoulin/yamete:rpi-${VERSION} .
publish:
	docker push jaymoulin/yamete
	cat manifest.yml | sed "s/\$$VERSION/${VERSION}/g" > manifest.yaml
	cat manifest.yaml | sed "s/\$$FULLVERSION/${FULLVERSION}/g" > manifest2.yaml
	mv manifest2.yaml manifest.yaml
	manifest-tool push from-spec manifest.yaml
