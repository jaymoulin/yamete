VERSION ?= 0.2.0
CACHE ?= --no-cache=1
FULLVERSION ?= ${VERSION}
archs ?= amd64-latest-stable i386-latest-stable armhf-latest-stable arm64-latest-stable aarch64-latest-stable
.PHONY: all build publish latest
all: build publish
qemu-arm-static:
	cp /usr/bin/qemu-arm-static .
qemu-aarch64-static:
	cp /usr/bin/qemu-aarch64-static .
build: qemu-aarch64-static qemu-arm-static
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
