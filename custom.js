let responseResult = (btclass, messageTitle, messageDescription)=> {

    return `<div class="alert alert-${btclass} alert-dismissible fade show" role="alert">
                <strong>${messageTitle}!</strong> ${messageDescription}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>`;

}