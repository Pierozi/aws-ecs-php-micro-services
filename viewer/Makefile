build: purge js css dist/viewer.js dist/viewer.css
.PHONY: build

purge:
	rm -rf css js dist
.PHONY: purge

dir:
	mkdir css js
.PHONY: dir

js:
	mkdir js
	curl https://static.hoa-project.net/Javascript/Hoa.js -o js/Hoa.js
	curl https://static.hoa-project.net/Javascript/Keynote.js -o js/Keynote.js
	curl https://static.hoa-project.net/Javascript/Hoa.Graph.js -o js/Hoa.Graph.js
	curl https://static.hoa-project.net/Javascript/Prism.js -o js/Prism.js
	curl https://static.hoa-project.net/Javascript/Prism.plugin.line.js -o js/Prism.plugin.line.js
.PHONY: js

css:
	mkdir css
	curl https://static.hoa-project.net/Css/Keynote.css -o css/Keynote.css
	curl https://static.hoa-project.net/Css/Leaflet.css -o css/Leaflet.css
	curl https://static.hoa-project.net/Css/Graph.css -o css/Graph.css
	curl https://static.hoa-project.net/Css/Code.css -o css/Code.css
	curl https://static.hoa-project.net/Css/UI.css -o css/UI.css
.PHONY: css

dist/viewer.js: node_modules
	mkdir dist
	./node_modules/uglify-js/bin/uglifyjs js/*.js -o dist/viewer.js
	curl https://static.hoa-project.net/Javascript/Prism.hoa.js -o dist/Prism.hoa.js

dist/viewer.css: node_modules
	cat css/*.css > dist/viewer.css

node_modules:
	npm install
