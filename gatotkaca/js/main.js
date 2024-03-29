var Sprite = function(name) {
    this.name = name;
    this.totalTimelapsed = 0;
    this.totalDuration = 0;
    this.list = [];
    this.currentImage = null;

    /*
    @params Image
    @params integer
    */
    this.add = function(image, duration) {
        var vm = this;
        if (this.currentImage == null) {
            this.currentImage = image;
        }

        vm.totalDuration += duration;
        this.list.push({
            image: image,
            duration: duration,
            totalDuration: vm.totalDuration
        });
    };

    this.getImage = function() {
        return this.currentImage;
    };

    this.update = function(timelapsed) {
        var vm = this;
        var i = 0;
        var total = 0;
        var x = null;

        this.totalTimelapsed += timelapsed;

        if (this.totalTimelapsed > this.totalDuration)
            this.totalTimelapsed = this.totalTimelapsed - this.totalDuration;

        for (i = 0; i < this.list.length; i++) {
            x = this.list[i];

            if (this.totalTimelapsed <= x.totalDuration) {
                this.currentImage = x.image;
                break;
            }
        }
    }

    this.reset = function() {
        this.totalTimelapsed = 0;
    }

    this.getTotalTimelapsed = function() {
        return this.totalTimelapsed;
    }
}

var MyGame = function(canvas) {
    this.canvas = canvas;
    this.context = null;

    this.width = canvas.width;
    this.height = canvas.height;

    this.images = [];
    this.counter = 0;

    this.sprite = null;
    this.startTime = null;
    this.endTime = null;
    this.diffTime = 0;

    this.init = function() {
        this.context = this.canvas.getContext("2d");
        this.loadAssets();
        this.startTime = new Date();
        this.endTime = new Date();
    };

    this.start = function() {
        var vm = this;

        if (this.isAssetsReady()) {
            if (this.sprite == null)
                this.prepareSprite();

            // this.loadAssets();
            // this.startTime = new Date();
            this.update(this.diffTime);
            this.endTime = new Date();
            this.diffTime = this.endTime - this.startTime;

            this.startTime = this.endTime;
        } else {
            this.drawLoading();
        }

        setTimeout(function() {
            vm.start();
        }, 10);
    };

    this.drawLoading = function() {
        this.clear();
        this.context.font = "30px Arial";
        this.context.fillText("Loading...", 10, 50);
    }

    this.prepareSprite = function() {
        var x = null;
        this.sprite = new Sprite('run');

        for (var i = 0; i < this.images.length; i++) {
            x = this.images[i];

            // console.log(x.resource);
            this.sprite.add(x.resource, 100);
        }

        // debugger;
    }

    this.update = function(elapsedTime) {
        var vm = this;

        this.sprite.update(elapsedTime);

        // console.log('update', elapsedTime, this.sprite.getImage());
        vm.clear();
        vm.context.drawImage(this.sprite.getImage(), 0, 0);
    }

    this.isAssetsReady = function() {
        var list = this.images;
        var isReady = true;
        var x = null;
        for (i = 0; i < list.length; i++) {
            x = list[i];
            if (!x.is_loaded) {
                isReady = false;
                break;
            }
        }

        return isReady;
    }

    this.loadAssets = function() {
        var vm = this;

        this.prepareImages();
        var list = this.images;

        var img = null;
        var x = null;
        for (i = 0; i < list.length; i++) {
            x = list[i];

            img = new Image();
            x.resource = img;

            tempFunc = function(img, index) {
                img.onload = function() {
                    vm.updateLoadedImage(index);
                }
            }

            tempFunc(img, i);
            img.src = x.url;
        }
    }

    this.updateLoadedImage = function(index) {
        // console.log(this, index);
        this.images[index].is_loaded = true;
    }

    this.getImagesName = function() {
        var list = [];
        var num = null;
        var max = 10;
        var i = 0;

        for (i = 1; i <= max; i++) {
            if (i < max)
                num = '0' + i;
            else 
                num = i;

            list.push('run_' + num + '.png');
        }

        return list;
    }

    this.prepareImages = function() {
        var list = this.getImagesName();
        var i = 0;

        var x = null;
        for (i = 0; i < list.length; i++) {
            x = list[i];
            this.images.push({
                'name': x,
                'is_loaded': false,
                'url': 'assets/' + x,
                'resource': null
            })
        }
    }

    this.clear = function() {
        this.context.clearRect(0, 0, this.width, this.height);
    }
}