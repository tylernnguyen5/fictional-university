import $ from 'jquery'

class MyNotes {
  // Initiating
  constructor() {
    this.events();
  }

  // Events
  events() {
    $("#my-notes").on('click', ".delete-note", this.deleteNote);
    $("#my-notes").on('click', ".edit-note", this.editNote.bind(this));
    $("#my-notes").on('click', ".update-note", this.updateNote.bind(this));
    $('.submit-note').on('click', this.createNote.bind(this))
  }


  // Methods
  deleteNote(e) {
    let thisNote = $(e.target).parents("li");
    
    $.ajax({
      beforeSend: xhr => xhr.setRequestHeader('X-WP-Nonce', universityData.nonce),
      url: universityData.root_url + "/wp-json/wp/v2/note/" + thisNote.data('id'),
      type: "DELETE",
      success:  res => {
                  thisNote.slideUp();
                  console.log("Congrats", res);

                  if (res.userNoteCount < 5) {
                    $(".note-limit-message").removeClass('active');
                  }
                },
      error: err => console.log("Sorry", err),
    });
  }

  updateNote(e) {
    let thisNote = $(e.target).parents("li");

    let ourUpdatePost = {
      'title': thisNote.find(".note-title-field").val(),
      'content': thisNote.find(".note-body-field").val(),
    };
    
    $.ajax({
      beforeSend: xhr => xhr.setRequestHeader('X-WP-Nonce', universityData.nonce),
      url: universityData.root_url + "/wp-json/wp/v2/note/" + thisNote.data('id'),
      type: "POST",
      data: ourUpdatePost,
      success:  res => {
                  this.makeNoteReadOnly(thisNote);
                  console.log("Congrats", res);
                },
      error: err => console.log("Sorry", err),
    });
  }

  createNote() {
    let ourNewPost = {
      'title': $(".new-note-title").val(),
      'content': $(".new-note-body").val(),
      'status': 'publish'
    };
    
    $.ajax({
      beforeSend: xhr => xhr.setRequestHeader('X-WP-Nonce', universityData.nonce),
      url: universityData.root_url + "/wp-json/wp/v2/note/",
      type: "POST",
      data: ourNewPost,
      success:  res => {
                  // Empty fields in the new note form
                  $(".new-note-title, .new-note-body").val('');

                  // Add new note to the list
                  $(`
                    <li data-id="${res.id}">
                      <input readonly class="note-title-field" value="${res.title.raw}">
                      <span class="edit-note"><i class="fa fa-pencil"></i>Edit</span>
                      <span class="delete-note"><i class="fa fa-trash-o"></i>Delete</span>

                      <textarea readonly class="note-body-field">${res.content.raw}</textarea>

                      <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right"></i> Save</span>
                    </li>
                  `).prependTo('#my-notes').hide().slideDown()

                  console.log("Congrats", res);
                },
      error:  res => {
                if (res.responseText == "You have reached your note limit.") {
                  $(".note-limit-message").addClass("active");
                }

                console.log("Sorry", res);
              },
    });
  }

  editNote(e) {
    let thisNote = $(e.target).parents("li");

    if (thisNote.data("state") == "editable") {
      this.makeNoteReadOnly(thisNote);
    } else {
      this.makeNoteEditable(thisNote);
    }
  }

  makeNoteEditable(thisNote) {
		// Switch to cancel button
		thisNote.find(".edit-note").html('<i class="fa fa-times"></i> Cancel');

		// Animate fields
		thisNote
			.find(".note-title-field, .note-body-field")
			.removeAttr("readonly")
			.addClass("note-active-field");

		// Show save button
		thisNote.find(".update-note").addClass("update-note--visible");

    // Update state
    thisNote.data("state", "editable");
  }

  makeNoteReadOnly(thisNote) {
		// Switch to edit button
		thisNote.find(".edit-note").html('<i class="fa fa-pencil"></i> Edit');

		// Remove animations
		thisNote
			.find(".note-title-field, .note-body-field")
			.attr("readonly", "readonly")
			.removeClass("note-active-field");

		// Hide save button
		thisNote.find(".update-note").removeClass("update-note--visible");

    //  Update state
    thisNote.data("state", "readonly");
  }
}

export default MyNotes;