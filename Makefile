VERSION ?= 1.18.4
CACHE ?= --no-cache=1
FULLVERSION ?= ${VERSION}
archs ?= amd64 arm32v6 arm64v8 i386
COMPOSER ?= update
.PHONY: all build publish test composer latest test-clean test-real ci
all: build publish latest
qemu-arm-static:
	cp /usr/bin/qemu-arm-static .
qemu-aarch64-static:
	cp /usr/bin/qemu-aarch64-static .
build: qemu-aarch64-static qemu-arm-static build/test-image
	docker run --rm -v ${PWD}:/app/ yamete:test php composer.phar install --no-dev -o; \
	$(foreach arch,$(archs), \
		cat docker/Dockerfile | sed "s/FROM php/FROM ${arch}\/php/g" > Dockerfile; \
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
composer: build/test-image
	image=`docker images yamete:test | wc -l`; \
	if [ "$${image}" -gt 1 ]; then \
		docker run --rm -v ${PWD}:/app/ yamete:test php composer.phar ${COMPOSER};\
	else\
		rm build/test-image;\
	fi
build/test-image:
	mkdir -p build
	touch qemu-mock-static
	cp docker/Dockerfile Dockerfile
	docker build -t yamete:test .
	docker run --rm -v ${PWD}:/app/ yamete:test wget https://raw.githubusercontent.com/composer/getcomposer.org/76a7060ccb93902cd7576b67264ad91c8a2700e2/web/installer -O composerinstall.php -q
	docker run --rm -v ${PWD}:/app/ yamete:test php composerinstall.php -q --quiet
	docker run --rm -v ${PWD}:/app/ yamete:test php composer.phar install
	touch build/test-image
test-real: build/test-image
	image=`docker images yamete:test | wc -l`; \
	if [ "$${image}" -gt 1 ]; then \
		cd tests;\
		grep -rnE "https?://" | sed -En "s/.*(https?[^'\"]+).*/\1/p" > ../build/list.txt;\
		cd ..;\
		docker run --rm --name yametestreal -v ${PWD}:/app/ -v ${PWD}:/dl yamete:test download -l /dl/build/list.txt -e /dl/build/error.txt -v --ansi;\
		exit `cat build/error.txt | wc -l`;\
	else\
		rm build/test-image;\
	fi
test: build/test-image
	image=`docker images yamete:test | wc -l`; \
	if [ "$${image}" -gt 1 ]; then \
		docker run --rm --name yametest -v ${PWD}:/app/ yamete:test php -d max_execution_time=5000 vendor/bin/phpunit;\
	else\
		rm build/test-image;\
	fi
test-clean:
	docker run --rm -v ${PWD}:/app/ yamete:test rm -Rf composer.phar composerinstall.php .composer
	docker rmi yamete:test
	rm build/test-image
yamete.zip:
	zip -R yamete.zip vendor/* docker/* src/* tests/* composer.json composer.lock converter Dockerfile download logo.png Makefile manifest.yml phpunit.xml qemu-mock-static README.md .editorconfig vendor/**/* src/**/* tests/**/*
ci:
	mkdir -p build/logs
	COMPOSER=phpcs make composer || true
	COMPOSER=phpmd make composer || true 
	COMPOSER=phploc make composer || true
	COMPOSER=phpcpd make composer || true
