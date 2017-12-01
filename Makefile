VERSION ?= 0.1.4
CACHE ?= --no-cache=1
FULLVERSION ?= ${VERSION}
archs = armhf-latest-stable aarch64-latest-stable arm64-latest-stable amd64-latest-stable i386-latest-stable
.PHONY: all build publish latest
all: build publish
build:
	$(foreach arch,$(archs), \
		cat docker/Dockerfile | sed "s/FROM alpine/FROM multiarch\/alpine:${arch}/g" > Dockerfile; \
		docker build -t jaymoulin/yamete:${VERSION}-$(arch) ${CACHE} .;\
	)
publish:
	docker push jaymoulin/yamete
	cat manifest.yml | sed "s/\$$VERSION/${VERSION}/g" > manifest.yaml
	cat manifest.yaml | sed "s/\$$FULLVERSION/${FULLVERSION}/g" > manifest2.yaml
	mv manifest2.yaml manifest.yaml
	manifest-tool push from-spec manifest.yaml
latest:
	VERSION=${VERSION} make build
	VERSION=${VERSION} make publish
	FULLVERSION=latest VERSION=${VERSION} make publish
