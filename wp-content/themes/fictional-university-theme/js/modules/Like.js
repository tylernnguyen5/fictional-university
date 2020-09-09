import $ from 'jquery'

class Like {
  // Initiate
  constructor() {
    this.events();
  }


  // Events
  events() {
    $('.like-box').on('click', this.ourClickDispatcher.bind(this));
  }


  // Methods
  ourClickDispatcher(e) {
    let currentLikeBox = $(e.target).closest('.like-box')

    if (currentLikeBox.attr('data-exists') == 'yes') {       // if the current user has already liked
      this.deleteLike(currentLikeBox);
    } else {
      this.createLike(currentLikeBox);
    }
  }
  
  createLike(currentLikeBox) {
    $.ajax({
      beforeSend: xhr => xhr.setRequestHeader('X-WP-Nonce', universityData.nonce),
      url: universityData.root_url + '/wp-json/university/v1/manageLike',
      type: 'POST',
      data: {'professorId': currentLikeBox.data('professor')},
      success:  res => {
                  // Fill heart icon
                  currentLikeBox.attr("data-exists", 'yes');

                  // Update like count
                  let likeCount = parseInt(currentLikeBox.find(".like-count").html(), 10);
                  likeCount++;
                  currentLikeBox.find(".like-count").html(likeCount);

                  // Update like id
                  currentLikeBox.attr("data-like", res);

                  console.log(res);
                },  
      error: err => console.log(err),
    })
  }
  
  deleteLike(currentLikeBox) {  
    $.ajax({
      beforeSend: xhr => xhr.setRequestHeader('X-WP-Nonce', universityData.nonce),
      url: universityData.root_url + '/wp-json/university/v1/manageLike',
      type: 'DELETE',
      data: { 'like': currentLikeBox.attr('data-like') },
      success:  res => {
                  // Unfill heart icon
                  currentLikeBox.attr("data-exists", 'no');

                  // Update like count
                  let likeCount = parseInt(currentLikeBox.find(".like-count").html(), 10);
                  likeCount--;
                  currentLikeBox.find(".like-count").html(likeCount);

                  // Update like id
                  currentLikeBox.attr("data-like", '');

                  console.log(res)
                },
      error: err => console.log(err),
    })
  }
}

export default Like;