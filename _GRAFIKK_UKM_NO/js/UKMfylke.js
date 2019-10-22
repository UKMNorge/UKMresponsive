UKMresponsive.kommuneliste = (filter_container) => {
    var self = {
        getSearchSelector: () => {
            return filter_container + ' input[name="search"]';
        },
        getKommuneSelector: () => {
            return filter_container + ' .kommune';
        },
        bind: () => {
            $(document).on('keyup', self.getSearchSelector(), self.filter);
        },

        filter: () => {
            var words = $(self.getSearchSelector()).val().toLowerCase().split(' ');
            $(self.getKommuneSelector()).hide();

            $(self.getKommuneSelector()).filter((index, element) => {
                var searchIn = $(element).attr('data-filter').toLowerCase();
                var found = false;
                words.forEach(function(word) {
                    if (searchIn.indexOf(word) !== -1) {
                        found = true;
                        return; // bryter ut av forEach
                    }
                });
                return found; // faktisk resultat
            }).show();

            self.addCountHelper();
        },

        addCountHelper: () => {
            var numShown = $(self.getKommuneSelector() + ':visible').length;
            $(filter_container).removeClass('found-none found-few found-many').attr('data-count', numShown);
            if (numShown == 0) {
                $(filter_container).addClass('found-none');
            } else if (numShown < 5) {
                $(filter_container).addClass('found-few');
            } else {
                $(filter_container).addClass('found-many');
            }
        }
    }

    self.bind();

    return self;
}