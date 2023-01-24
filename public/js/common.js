//set current nav-link active
$('a[data-name="' + location.pathname.split("/")[1] + '"]').addClass("active");

//add headers to all the ajax requests
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

//initialize datatable
$("table")
    .not("#globalConfig, #features, #pages, #languages")
    .DataTable({
        responsive: true,
        autoWidth: false,
        order: [0, "desc"],
        pageLength: 50,
        lengthMenu: [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, "All"],
        ],
        language: {
            "lengthMenu": languages.lengthMenu,
            "zeroRecords": languages.zeroRecords,
            "info": languages.info,
            "infoEmpty": languages.infoEmpty,
            "infoFiltered": languages.infoFiltered,
            "search": languages.search,
            "paginate": {
                "next": languages.next,
                "previous": languages.previous,
            }
        }
    });

//initialize global config table
$("#globalConfig, #languages").DataTable({
    responsive: true,
    autoWidth: false,
    pageLength: 50,
    lengthMenu: [
        [5, 10, 25, 50, -1],
        [5, 10, 25, 50, "All"],
    ],
    language: {
        "lengthMenu": languages.lengthMenu,
        "zeroRecords": languages.zeroRecords,
        "info": languages.info,
        "infoEmpty": languages.infoEmpty,
        "infoFiltered": languages.infoFiltered,
        "search": languages.search,
        "paginate": {
            "next": languages.next,
            "previous": languages.previous,
        }
    }
});

//show success toaster
function showSuccess(message) {
    toastr.success(message);
}

//show warning toaster
function showInfo(message) {
    toastr.info(message);
}

//show error toaster
function showError(message) {
    toastr.error(message || languages.error_occurred);
}

//ajax call to update content
$("#contentEdit").on("submit", function(e) {
    e.preventDefault();

    $("#save").attr("disabled", true);

    $.ajax({
            url: "/update-page",
            data: {
                id: $("#id").val(),
                value: $("#content").summernote("code"),
            },
            type: "post",
        })
        .done(function(data) {
            data = JSON.parse(data);
            $("#save").attr("disabled", false);

            if (data.success) {
                showSuccess(languages.data_updated);
            } else {
                showError();
            }
        })
        .catch(function() {
            showError();
            $("#save").attr("disabled", false);
        });
});

//ajax call to global config
$("#globalConfigEdit").on("submit", function(e) {
    e.preventDefault();

    $("#save").attr("disabled", true);

    let form = new FormData();
    form.append("id", $("#id").val());
    form.append("key", $("#key").val());
    form.append("value", $("#value").val());
    form.append(
        "image",
        $("#value").prop("files") ? $("#value").prop("files")[0] : ""
    );

    $.ajax({
            url: "/update-global-config",
            data: form,
            type: "post",
            cache: false,
            contentType: false,
            processData: false,
        })
        .done(function(data) {
            data = JSON.parse(data);
            $("#save").attr("disabled", false);

            if (data.success) {
                showSuccess(languages.data_updated);
            } else {
                showError(data.error);
            }
        })
        .catch(function() {
            showError();
            $("#save").attr("disabled", false);
        });
});

//ajax call to update user status
$(".user-status").on("click", function() {
    let currentRow = $(this);
    let userId = currentRow.data("id");
    let checked = currentRow.is(":checked");

    currentRow.attr("disabled", true);

    $.ajax({
            url: "/update-user-status",
            type: "post",
            data: {
                id: userId,
                checked: checked,
            },
        })
        .done(function(data) {
            data = JSON.parse(data);
            currentRow.attr("disabled", false);

            if (data.success) {
                showSuccess(languages.data_updated);
            } else {
                showError(data.error);
                currentRow.prop("checked", true);
            }
        })
        .catch(function() {
            currentRow.attr("disabled", false);
            showError();
        });
});

//ajax call to verify license
$("#verifyLicense").on("click", function() {
    $(this).attr("disabled", true);

    $.ajax({
            url: "/verify-license",
        })
        .done(function(data) {
            data = JSON.parse(data);
            $("#verifyLicense").attr("disabled", false);

            if (data.success) {
                showSuccess(languages.valid_license + data.type);
            } else {
                showError(languages.invalid_license + data.error);
            }
        })
        .catch(function() {
            $("#verifyLicense").attr("disabled", false);
            showError();
        });
});

//ajax call to uninstall license
$("#uninstallLicense").on("click", function() {
    if (!confirm(languages.confirmation)) return;

    $(this).attr("disabled", true);

    $.ajax({
            url: "/uninstall-license",
        })
        .done(function(data) {
            data = JSON.parse(data);
            $("#uninstallLicense").attr("disabled", false);

            if (data.success) {
                showSuccess(languages.license_uninstalled);
            } else {
                showError(
                    languages.license_uninstalled_failed + data.error
                );
            }
        })
        .catch(function() {
            $("#uninstallLicense").attr("disabled", false);
            showError();
        });
});

//ajax call to check for update
$("#checkForUpdate").on("click", function() {
    $(this).attr("disabled", true);

    $.ajax({
            url: "/check-for-update",
        })
        .done(function(data) {
            data = JSON.parse(data);

            if (data.success) {
                $("#downloadUpdate").removeAttr("hidden");
                let changelog = '';
                $.each(data.changelog, function(key, value) {
                    changelog += '<b>V ' + key + ': </b>' + '<br>' + value + '<br><br>';
                });
                $("#changelog").html(changelog || "-");
                showSuccess(languages.update_available + data.version);
            } else if (data.error) {
                showError(data.error);
            } else {
                $("#checkForUpdate").attr("disabled", false);
                showInfo(
                    languages.already_latest_version +
                    data.version
                );
            }
        })
        .catch(function() {
            $("#checkForUpdate").attr("disabled", false);
            showError();
        });
});

//ajax call to download the update
$("#downloadUpdate").on("click", function() {
    $(this).attr("disabled", true);

    $.ajax({
            url: "/download-update",
        })
        .done(function(data) {
            data = JSON.parse(data);
            $("#downloadUpdate").removeAttr("hidden");

            if (data.success) {
                showSuccess(
                    languages.application_updated
                );
            } else if (data.error) {
                showError(data.error);
            } else {
                $("#downloadUpdate").attr("disabled", false);
                showError(languages.update_failed + data.error);
            }
        })
        .catch(function() {
            $("#downloadUpdate").attr("disabled", false);
            showError();
        });
});

//ajax call to check signaling
$("#checkSignaling").on("click", function() {
    $("#checkSignaling").attr("disabled", true);

    $.ajax({
            url: "/check-signaling",
        })
        .done(function(data) {
            data = JSON.parse(data);
            $("#checkSignaling").attr("disabled", false);
            $("#status").html(data.status);

            if (data.status == "Running") {
                $("#status")
                    .removeClass("badge-danger")
                    .addClass("badge-success");
            } else {
                $("#status")
                    .removeClass("badge-success")
                    .addClass("badge-danger");
            }
        })
        .catch(function() {
            $("#checkSignaling").attr("disabled", false);
            showError();
        });
});

//show reported image
$(".reported-image").on("click", function() {
    $("#reportedImageModal").modal("show");
    reportedImage.src = $(this).attr("src");
});

//ajax call to ignore the user
$(".ignore").on("click", function() {
    if (confirm(languages.confirmation)) {
        let currentRow = $(this);
        currentRow.attr("disabled", true);

        let form = new FormData();
        form.append("id", currentRow.data("id"));

        $.ajax({
                url: "ignore-user",
                data: form,
                type: "post",
                cache: false,
                contentType: false,
                processData: false,
            })
            .done(function(data) {
                data = JSON.parse(data);

                if (data.success) {
                    currentRow.parent().parent().remove();
                    showSuccess(languages.user_ignored);
                } else {
                    showError(data.error);
                }
            })
            .catch(function() {
                showError();
            });
    }
});

//ajax call to ban the user
$(".ban").on("click", function() {
    if (confirm(languages.confirmation)) {
        let currentRow = $(this);
        currentRow.attr("disabled", true);

        let form = new FormData();
        form.append("id", currentRow.data("id"));

        $.ajax({
                url: "ban-user",
                data: form,
                type: "post",
                cache: false,
                contentType: false,
                processData: false,
            })
            .done(function(data) {
                data = JSON.parse(data);

                if (data.success) {
                    currentRow.parent().parent().remove();
                    showSuccess(languages.user_banned);
                } else {
                    showError(data.error);
                }
            })
            .catch(function() {
                showError();
            });
    }
});

//ajax call to unban the user
$(".unban").on("click", function() {
    if (confirm(languages.confirmation)) {
        let currentRow = $(this);
        currentRow.attr("disabled", true);

        let form = new FormData();
        form.append("ip", currentRow.data("ip"));

        $.ajax({
                url: "unban-user",
                data: form,
                type: "post",
                cache: false,
                contentType: false,
                processData: false,
            })
            .done(function(data) {
                data = JSON.parse(data);

                if (data.success) {
                    currentRow.parent().parent().remove();
                    showSuccess(languages.user_unbanned);
                } else {
                    showError(data.error);
                }
            })
            .catch(function() {
                showError();
            });
    }
});

//ajax call to update feature status
$(".feature-status").on("click", function() {
    let currentRow = $(this);
    let featureId = currentRow.data("id");
    let checked = currentRow.is(":checked");

    currentRow.attr("disabled", true);

    $.ajax({
            url: "/update-feature-status",
            type: "post",
            data: {
                id: featureId,
                checked: checked,
            },
        })
        .done(function(data) {
            data = JSON.parse(data);
            currentRow.attr("disabled", false);

            if (data.success) {
                showSuccess(languages.data_updated);
            } else {
                showError(data.error);
                currentRow.prop("checked", true);
            }
        })
        .catch(function() {
            currentRow.attr("disabled", false);
            showError();
        });
});

//ajax call to update feature paid
$(".feature-paid").on("click", function() {
    let currentRow = $(this);
    let featureId = currentRow.data("id");
    let checked = currentRow.is(":checked");

    currentRow.attr("disabled", true);

    $.ajax({
            url: "/update-feature-paid",
            type: "post",
            data: {
                id: featureId,
                checked: checked,
            },
        })
        .done(function(data) {
            data = JSON.parse(data);
            currentRow.attr("disabled", false);

            if (data.success) {
                showSuccess(languages.data_updated);
            } else {
                showError(data.message);
                currentRow.prop("checked", false);
            }
        })
        .catch(function() {
            currentRow.attr("disabled", false);
            showError();
        });
});

//check the checkAll checkbox if all the checkboxes are checked
$("input:checkbox[name='reported_users[]']").on("change", function() {
    var totalCheckBoxes = $("input:checkbox[name='reported_users[]']").length;
    var totalCheckedBoxes = $(
        "input:checkbox[name='reported_users[]']:checked"
    ).length;

    if (totalCheckBoxes === totalCheckedBoxes) {
        $("#checkAll").prop("checked", true);
    } else {
        $("#checkAll").prop("checked", false);
    }
});

//check/uncheck all the checkboxes if the checkAll is checked/unchecked
$("#checkAll").on("click", function() {
    if ($("input:checkbox").prop("checked")) {
        $("input:checkbox[name='reported_users[]']").prop("checked", true);
    } else {
        $("input:checkbox[name='reported_users[]']").prop("checked", false);
    }
});

//check the checkAll checkbox if all the checkboxes are checked
$("input:checkbox[name='banned_users[]']").on("change", function() {
    var totalCheckBoxes = $("input:checkbox[name='banned_users[]']").length;
    var totalCheckedBoxes = $(
        "input:checkbox[name='banned_users[]']:checked"
    ).length;

    if (totalCheckBoxes === totalCheckedBoxes) {
        $("#checkAll").prop("checked", true);
    } else {
        $("#checkAll").prop("checked", false);
    }
});

//check/uncheck all the checkboxes if the checkAll is checked/unchecked
$("#checkAll").on("click", function() {
    if ($("input:checkbox").prop("checked")) {
        $("input:checkbox[name='banned_users[]']").prop("checked", true);
    } else {
        $("input:checkbox[name='banned_users[]']").prop("checked", false);
    }
});

//ajax call to ignore the records
$("#bulkIgnore").on("click", function() {
    let btn = $(this);

    let ids = [];
    $("input:checkbox[name='reported_users[]']:checked").each(function(i) {
        ids[i] = $(this).val();
    });

    if (!ids.length) {
        showError(languages.select_record);
        btn.attr('disabled', false);
        return;
    }

    if (!confirm(languages.confirmation)) return;
    btn.attr('disabled', true);

    $.ajax({
            url: "/bulk-ignore-users",
            type: "post",
            data: {
                ids: JSON.stringify(ids),
            },
        })
        .done(function(data) {
            data = JSON.parse(data);
            btn.attr('disabled', false);

            if (data.success) {
                showSuccess(languages.data_updated);
                window.location.reload();
            } else {
                showError(data.message);
            }
        })
        .catch(function() {
            btn.attr('disabled', false);
            showError();
        });
});

//ajax call to ban the records
$("#bulkBan").on("click", function() {
    let btn = $(this);

    let ids = [];
    $("input:checkbox[name='reported_users[]']:checked").each(function(i) {
        ids[i] = $(this).val();
    });

    if (!ids.length) {
        showError(languages.select_record);
        btn.attr('disabled', false);
        return;
    }

    if (!confirm(languages.confirmation)) return;
    btn.attr('disabled', true);

    $.ajax({
            url: "/bulk-ban-users",
            type: "post",
            data: {
                ids: JSON.stringify(ids),
            },
        })
        .done(function(data) {
            data = JSON.parse(data);
            btn.attr('disabled', false);

            if (data.success) {
                showSuccess(languages.data_updated);
                window.location.reload();
            } else {
                showError(data.message);
            }
        })
        .catch(function() {
            btn.attr('disabled', false);
            showError();
        });
});

//ajax call to unban the records
$("#bulkUnban").on("click", function() {
    let btn = $(this);

    let ids = [];
    $("input:checkbox[name='banned_users[]']:checked").each(function(i) {
        ids[i] = $(this).val();
    });

    if (!ids.length) {
        showError(languages.select_record);
        btn.attr('disabled', false);
        return;
    }

    if (!confirm(languages.confirmation)) return;
    btn.attr('disabled', true);

    $.ajax({
            url: "/bulk-unban-users",
            type: "post",
            data: {
                ids: JSON.stringify(ids),
            },
        })
        .done(function(data) {
            data = JSON.parse(data);
            btn.attr('disabled', false);

            if (data.success) {
                showSuccess(languages.data_updated);
                window.location.reload();
            } else {
                showError(data.message);
            }
        })
        .catch(function() {
            btn.attr('disabled', false);
            showError();
        });
});

//ajax call to add language
$("#languagesAdd").on("submit", function(e) {
    e.preventDefault();

    $("#save").attr("disabled", true);

    let form = new FormData();
    form.append("code", $("#code").val());
    form.append("name", $("#name").val());
    form.append("direction", $("#direction").val());
    form.append("default", $("#default").val());
    form.append("status", $("#status").val());
    form.append("file", $("#file").prop("files")[0]);

    $.ajax({
            url: "/create-language",
            data: form,
            type: "post",
            cache: false,
            contentType: false,
            processData: false,
        })
        .done(function(data) {
            data = JSON.parse(data);
            $("#save").attr("disabled", false);

            if (data.success) {
                showSuccess(languages.data_added);
                location.href = '/languages';
            } else {
                showError(data.message);
            }
        })
        .catch(function() {
            showError();
            $("#save").attr("disabled", false);
        });
});

//ajax call to edit language
$("#languagesEdit").on("submit", function(e) {
    e.preventDefault();

    $("#save").attr("disabled", true);

    let form = new FormData();
    form.append("id", $("#id").val());
    form.append("direction", $("#direction").val());
    form.append("default", $("#default").val());
    form.append("status", $("#status").val());
    form.append("file", $("#file").prop("files").length ? $("#file").prop("files")[0] : "");

    $.ajax({
            url: "/update-language",
            data: form,
            type: "post",
            cache: false,
            contentType: false,
            processData: false,
        })
        .done(function(data) {
            data = JSON.parse(data);
            $("#save").attr("disabled", false);

            if (data.success) {
                showSuccess(languages.data_updated);
                location.href = '/languages';
            } else {
                showError(data.message);
            }
        })
        .catch(function() {
            showError();
            $("#save").attr("disabled", false);
        });
});

//ajax call to delete the language
$(".deleteLanguage").on("click", function() {
    if (confirm(languages.confirmation)) {
        let currentRow = $(this);
        currentRow.attr("disabled", true);

        let form = new FormData();
        form.append("id", currentRow.data("id"));

        $.ajax({
                url: "languages/delete",
                data: form,
                type: "post",
                cache: false,
                contentType: false,
                processData: false,
            })
            .done(function(data) {
                data = JSON.parse(data);

                if (data.success) {
                    currentRow.parent().parent().remove();
                    showSuccess(languages.data_deleted);
                } else {
                    showError(data.message);
                    currentRow.attr("disabled", false);
                }
            })
            .catch(function() {
                showError();
            });
    }
});