VERSION ?= 0.8.0
CACHE ?= --no-cache=1
FULLVERSION ?= ${VERSION}
archs ?= amd64 arm32v6 arm64v8 i386
.PHONY: all build publish test build-test-image update latest
all: build publish latest
qemu-arm-static:
	cp /usr/bin/qemu-arm-static .
qemu-aarch64-static:
	cp /usr/bin/qemu-aarch64-static .
build: qemu-aarch64-static qemu-arm-static
	$(foreach arch,$(archs), \
		cat docker/Dockerfile | sed "s/FROM alpine/FROM ${arch}\/alpine/g" > Dockerfile; \
		docker build -t jaymoulin/yamete:${VERSION}-$(arch) --build-arg VERSION=${VERSION} ${CACHE} .;\
	)
publish:
	docker push jaymoulin/yamete
	cat manifest.yml | sed "s/\$$VERSION/${VERSION}/g" > manifest.yaml
	cat manifest.yaml | sed "s/\$$FULLVERSION/${FULLVERSION}/g" > manifest2.yaml
	mv manifest2.yaml manifest.yaml
	manifest-tool push from-spec manifest.yaml
latest:
	FULLVERSION=latest VERSION=${VERSION} make publish
update: build-test-image
	docker run --rm --name yametest -ti -v ${PWD}:/root/ yamete:test php composer.phar update
	docker run --rm --name yametest -ti -v ${PWD}:/root/ yamete:test rm -Rf composer.phar composerinstall.php .composer
	docker rmi yamete:test
build-test-image:
	cp docker/Dockerfile Dockerfile
	docker build -t yamete:test .
	docker run --rm --name yametest -ti -v ${PWD}:/root/ yamete:test wget https://raw.githubusercontent.com/composer/getcomposer.org/1b137f8bf6db3e79a38a5bc45324414a6b1f9df2/web/installer -O composerinstall.php -q
	docker run --rm --name yametest -ti -v ${PWD}:/root/ yamete:test php composerinstall.php -q --quiet
	docker run --rm --name yametest -ti -v ${PWD}:/root/ yamete:test php composer.phar install
test: build-test-image
	docker run --rm --name yametest -ti -v ${PWD}:/root/ yamete:test php -d max_execution_time=5000 vendor/bin/phpunit
	docker run --rm --name yametest -ti -v ${PWD}:/root/ yamete:test rm -Rf composer.phar composerinstall.php .composer
	docker rmi yamete:test
