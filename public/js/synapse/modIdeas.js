/*
 *      Spécifique au module IDEAS
 *      jda@ewbs.be
 *      
 *      Composé de 2 parties : GESTION COMMENTAIRES & GESTION ETAT

 *       doc à écrire ... non mais sérieux ...
 *       ... et code à réécrire ... c'est devenu 
 * 
 */

//gestion des commentaires
var commentsList = null;
var commentsNone = null;
var commentsTemplate = null;
var commentsTemplateState = null;
var commentsContent = null;
var commentsSubmit = null;
var commentsAjaxList = "/admin/ideas/comments/{{ID}}/list";
var commentsAjaxNew = "/admin/ideas/comments/{{ID}}/comment";
var commentsAjaxDelete = "/admin/ideas/comments/{{ID}}/delete";
var commentsAjaxEdit = "/admin/ideas/comments/{{ID}}/edit";
var domToken = null;
var domModalEdit = null;
var domModalDelete = null;

//gestion des etats
var domStateHiddenInput = null;
var domStateBtnModify = null;
var domStateBtnCancel = null;

/**
 * INIT
 * @param {type} param
 */
$(document).ready( function () { 
        // pour le changement d'état (formulaire de modification d'un projet de simplif)
        if ($("input#changestate").length) {
            modIdeasStates_init();
        }
        
        // gestion des commentaires
        if ($("#comments-list").length) {
            modIdeasComments_init();
        }
    
});


/**
 * Initialisation de la partie "gestion des commentaires"
 * @returns {undefined}
 */
function modIdeasComments_init() {

        commentsList = $("#comments-list");
        commentsNone = $("#comments-none");
        commentsTemplate = $("#comments-template");
        commentsTemplateState = $("#comments-template-state");
        commentsIdeaId = commentsList.data("idea-id");
        commentsAjaxList = commentsAjaxList.replace('{{ID}}', commentsIdeaId);
        commentsAjaxNew = commentsAjaxNew.replace('{{ID}}', commentsIdeaId);
        domModalEdit = $("#comments-modal-edit");
        domModalDelete = $("#comments-modal-delete");
        
        
        if ($("#_token").length) {
            domToken = $("#_token");
            token = domToken.val();
        }
        
        if ( $("#comments-submit").length ) {
            
            commentsContent = $("#comments-content");
            commentsSubmit = $("#comments-submit");
            
            commentsSubmit.click (function () {
                var content = commentsContent.val();
                if (content.length > 0) {
                    $.ajax({
                        url:commentsAjaxNew,
                        method: 'POST',
                        data: { comment: content, _token: token }
                    })
                    .done( function (json) {
                        if (json.error != "undefined" && json.error == false) {
                            commentsContent.val("");
                            modIdeasComments_refreshList(true);
                        }
                        else {
                            alert('Erreur de communication avec Synapse ('+json.return+')');
                        }
                    })
                    .error( function (xhr, ajaxOptions, thrownError) {
                        alert('Erreur de communication avec Synapse ('+thrownError+')');
                    });
                }
            });
            
        }
        
        modIdeasComments_refreshList();
                
}

function modIdeasComments_refreshList(withHighLight) {

        withHighLight = typeof withHighLight !== "undefined" ? true : false;
        
        commentsList.hide();
        commentsList.empty();
        commentsNone.show();

        if (commentsIdeaId > 0) {
            $.ajax({
                url: commentsAjaxList
            })
            .done(function (json) {
                if ( json.comments.length > 0) {
                    $.each( json.comments, function (i, comment) {
                        switch (comment.type) {
                            case 'comment':
                                var $li = $(commentsTemplate.html());
                                $li.attr("data-comment-id", comment.id);
                                $li.find(".date").html(comment.shortdate);
                                $li.find("strong.comments-username").html(comment.username);
                                $li.find("p.comment-content").html(comment.comment.replace(/(?:\r\n|\r|\n)/g, '<br/>'));
                                $li.find("img.comments-useravatar").attr("src", comment.avatar);
                                commentsList.append($li);
                                break;
                            case 'state':
                                var $li = $(commentsTemplateState.html());
                                $li.find(".date").html(comment.shortdate);
                                $li.find("strong.comments-username").html(comment.username);
                                $li.find("strong.comments-state").html(comment.state).after('<br/>'+comment.comment.replace(/(?:\r\n|\r|\n)/g, '<br/>'));
                                $li.find("img.comments-useravatar").attr("src", comment.avatar);
                                commentsList.append($li);
                                break;
                        }
                    });
                    
                    //si on l'a demandé, on mets le premier commentaire en haut de liste en évidence
                    if (withHighLight) {
                        commentsList.find("div:first div.content").effect('highlight', {}, 1500);
                    }
                    commentsNone.hide();
                    commentsList.show();
                    
                    //on active les boutons d'édit si disponibles
                    commentsList.find("a").unbind('click');
                    commentsList.find("a").click(function () {
                        var $parentLi = $(this).parents("div.chat-conv");
                        var content=$parentLi.find("p.comment-content").html();
                        content=content.replace(/<br\s*\/?>/mg, "\r\n");
                        domModalEdit.find("textarea").val(content);
                        domModalEdit.find("#comment-id").val($parentLi.data("comment-id"));
                        domModalEdit.find(".modal-title span").html($parentLi.find("strong").html());
                        domModalEdit.modal(); 
                    });
                    
                    //edition
                    $("#comments-modal-button-edit").unbind("click");
                    $("#comments-modal-button-edit").click( function () {
                        var content = domModalEdit.find("textarea").val();
                        var commentId = domModalEdit.find("#comment-id").val();
                        if (content.length) {
                            $.ajax({
                                url:commentsAjaxEdit.replace('{{ID}}', commentId),
                                    method: 'POST',
                                    data: { _token: token, comment: content }
                                    })
                                    .done( function (json) {
                                        if (json.error != "undefined" && json.error == false) {
                                            domModalEdit.modal('hide');
                                            modIdeasComments_refreshList();
                                        }
                                        else {
                                            alert('Erreur de communication avec Synapse ('+json.return+')');
                                        }
                                    })
                                    .error( function (xhr, ajaxOptions, thrownError) {
                                        alert('Erreur de communication avec Synapse ('+thrownError+')');
                                    }); 
                        }
                    });
                    
                    //suppression
                    $("#comments-modal-button-delete").unbind("click");
                    $("#comments-modal-button-delete").click( function () {
                        domModalDelete.find("#comments-modal-confirm-button-delete")
                                .unbind("click")
                                .click(function() {
                                    $.ajax({
                                        url:commentsAjaxDelete.replace('{{ID}}', domModalEdit.find("#comment-id").val()),
                                        method: 'POST',
                                        data: { _token: token }
                                    })
                                    .done( function (json) {
                                        if (json.error != "undefined" && json.error == false) {
                                            modIdeasComments_refreshList();
                                        }
                                        else {
                                            alert('Erreur de communication avec Synapse ('+json.return+')');
                                        }
                                    })
                                    .error( function (xhr, ajaxOptions, thrownError) {
                                        alert('Erreur de communication avec Synapse ('+thrownError+')');
                                    })
                                    .always( function () {
                                        domModalDelete.modal('hide');
                                        domModalEdit.modal('hide');
                                    });                                    
                        });
                        domModalDelete.modal();
                    });
                }
            })
            .error( function (xhr, ajaxOptions, thrownError) {
                    alert('Erreur de communication avec Synapse ('+thrownError+')');
            });
        }
}



/**
 * Initialisation des modifications d'état dans le module IDEAS
 * Ceci n'existe que sur le formulaire d'édition d'un projet de simplif
 * @returns {undefined}
 */
function modIdeasStates_init() {
    
        //init des variables utilisées
        domStateHiddenInput = $("input#changestate");
        domStateBtnCancel = $("a#state-button-cancel");
        domStateBtnModify = $("a#state-button-modify");
        
        // premier affichage : masquer ce qui ne doit pas être visible
        $("div.state-formgroup").hide();
        domStateBtnCancel.hide();
        domStateHiddenInput.val(0);
        
        //click sur "modifier l'état"
        domStateBtnModify.click( function () {
            domStateHiddenInput.val(1);
            domStateBtnCancel.show();
            domStateBtnModify.hide();
            $("div.state-formgroup").effect('highlight', {}, 1750);
        });
        
        //click sur "annuler"
        domStateBtnCancel.click( function () {
            domStateHiddenInput.val(0);
            domStateBtnCancel.hide();
            domStateBtnModify.show();
            $("div.state-formgroup").hide();
        });

}