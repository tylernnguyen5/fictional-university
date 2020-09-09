import $ from 'jquery'

class Search {
  // Initiating
  constructor() {
    this.addSearchHTML();

    this.openButton = $(".js-search-trigger");
    this.closeButton = $(".search-overlay__close");
    this.searchOverlay = $(".search-overlay");
    this.searchField = $('#search-term');
    this.resultsDiv = $("#search-overlay__results");

    this.events();

    this.isOverlayOpen = false;
    this.isSpinnerVisible = false;
    this.previousValue;
    this.typingTimer;
  }

  // Events
  events() {
    this.openButton.on("click", this.openOverlay.bind(this));
    this.closeButton.on("click", this.closeOverlay.bind(this));

    $(document).on("keydown", this.keyPressDispatcher.bind(this));

    this.searchField.on('keyup', this.typingLogic.bind(this));
  }

  // Methods
  openOverlay() {
    this.searchOverlay.addClass("search-overlay--active");
    $("body").addClass('body-no-scroll');
    this.searchField.val('');
    this.resultsDiv.html('');
    setTimeout( () => this.searchField.focus(), 310);
    this.isOverlayOpen = true;

    return false;
  }
  
  closeOverlay() {
    this.searchOverlay.removeClass("search-overlay--active");
    $("body").removeClass('body-no-scroll');
    this.isOverlayOpen = false;
  }

  keyPressDispatcher(e) {
    if (e.keyCode == 83 && !this.isOverlayOpen && !$("input, textarea").is(':focus')) {   // The 3rd condition is for not triggering overlay when currently focusing / typing other input fields
      this.openOverlay();
    }

    if (e.keyCode == 27 && this.isOverlayOpen) {
      this.closeOverlay();
    }
  }

  typingLogic() {

    if (this.searchField.val() != this.previousValue) {   // When the user doesn't change the input value by pressing keys such as arrow keys, the code below won't kickoff
      clearTimeout(this.typingTimer);

      if (this.searchField.val()) {   // If there's a value in the search field
        if (!this.isSpinnerVisible) {   // Load up the spinner if not visible
          this.resultsDiv.html('<div class="spinner-loader"></div>');
          this.isSpinnerVisible = true;
        }

        this.typingTimer = setTimeout(this.getResults.bind(this), 750);  // Sending request to the server

      } else {  // If the search field is completely empty, there's no need to send a request to the server
        this.resultsDiv.html('');
        this.isSpinnerVisible = false;
      }

    }

    this.previousValue = this.searchField.val();
  }

  getResults() {  
    // Variable 'universityData' via wp_localize_script() from the functions.php
    $.getJSON(universityData.root_url + '/wp-json/university/v1/search?term=' + this.searchField.val(), results => {
      this.resultsDiv.html(`
        <div class="row">

          <div class="one-third">
            <h2 class="search-overlay__section-title">General Information</h2>
            ${results.generalInfo.length ? '<ul class="link-list min-list">' : '<p>No general information matches that search.</p>'}
              ${results.generalInfo.map(item => `<li><a href="${item.permalink}">${item.title}</a> ${item.postType == 'post' ? `by ${item.authorName}` : ''}</li>`).join('')}
            ${results.generalInfo.length ? '</ul>' : ''}
          </div>

          <div class="one-third">
            <h2 class="search-overlay__section-title">Programs</h2>
            ${results.programs.length ? '<ul class="link-list min-list">' : `<p>No programs match that search. <a href="${universityData.root_url}/programs">View all programs.</a></p>`}
              ${results.programs.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join('')}
            ${results.programs.length ? '</ul>' : ''}

            <h2 class="search-overlay__section-title">Professors</h2>
            ${results.professors.length ? '<ul class="professor-cards">' : `<p>No campuses match that search.</p>`}
              ${results.professors.map(item => `
                <li class="professor-card__list-item">
                  <a class="professor-card" href="${item.permalink}">
                    <img class="professor-card__image" src="${item.image}">
                    <span class="professor-card__name">${item.title}</span>
                  </a>
                </li>
              `).join('')}
            ${results.professors.length ? '</ul>' : ''}
          </div>
          
          <div class="one-third">
            <h2 class="search-overlay__section-title">Campuses</h2>
            ${results.campuses.length ? '<ul class="link-list min-list">' : `<p>No campuses match that search. <a href="${universityData.root_url}/campuses">View all campuses.</a></p>`}
              ${results.campuses.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join('')}
            ${results.campuses.length ? '</ul>' : ''}

            <h2 class="search-overlay__section-title">Events</h2>
            ${results.events.length ? '' : `<p>No events match that search. <a href="${universityData.root_url}/events">View all events.</a></p>`}
              ${results.events.map(item => `
                <div class="event-summary">
                  <a class="event-summary__date t-center" href="${item.permalink}">
                    <span class="event-summary__month">${item.month}</span>
                    <span class="event-summary__day">${item.day}</span>
                  </a>
                  <div class="event-summary__content">
                    <h5 class="event-summary__title headline headline--tiny"><a href="${item.permalink}">${item.title}</a></h5>
                    <p>${item.description}<a href="${item.permalink}" class="nu gray">Learn more</a></p>
                  </div>
                </div>
              `).join('')}
            

          </div>
          
        </div>
      `);

      this.isSpinnerVisible = false;
    });

  }

  addSearchHTML() {   // Adding search overlay at the bottom of the HTML documemnt. After the <footer>
    $("body").append(`
      <div class="search-overlay">
        <div class="search-overlay__top">
          <div class="container">
            <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
            <input type="text" class="search-term" placeholder="What are you looking for?" autocomplete="off" id="search-term">
            <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
          </div>

          <div class="container">
            <div id="search-overlay__results">
              
            </div>
          </div>
        </div>
      </div>
    `);
  }
}

export default Search;