if (window.Dropzone) {
    Dropzone.options.galleryUploader = {
        paramName: 'fileupload',
        init: function() {
            this.on('addedfile', (file, xhr, formData) => {
                $('#gallery-uploader').html(`
                    <div class="progress w-100">
                        <div class="progress-bar" id="gallery-uploader-progress" role="progressbar" style="width: 0" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>`
                );
            });
            this.on('sending', (file, xhr, formData) => {
                const lastSeparator = file.name.lastIndexOf('.');
                if (lastSeparator > -1) {
                    formData.append('name', file.name.substring(0, lastSeparator));
                } else {
                    formData.append('name', file.name);
                }
            });
            this.on('queuecomplete', (file) => {
                setTimeout(() => {
                    location.reload();
                }, 500);
            });
            this.on('totaluploadprogress', (progress) => {
                if (progress > 100 || progress < 0) {
                    return;
                }

                $('#gallery-uploader-progress').width(`${progress}%`);
                $('#gallery-uploader-progress').prop('aria-valuenow', progress);
            });
        },
    };
}

$(document).ready(function() {
    let bulkSelected = [];

    $('#uploader-script-modal').modal({
        show: false,
    });

    $('#bulk-buttons').collapse({
        toggle: false,
    });

    $('.bulk-select').change(function() {
        const fileName = $(this).parent().parent().parent().data('filename');
        if (this.checked) {
            bulkSelected.push(fileName);
        } else {
            bulkSelected = bulkSelected.filter((file) => file !== fileName);
        }
        if (bulkSelected.length > 0) {
            $('#bulk-buttons').collapse('show');
        } else {
            $('#bulk-buttons').collapse('hide');
        }
    });

    $('#bulk-delete').on('click', () => {
        const confirmation = confirm('Are you sure you want to permanently delete the selected uploads? This action cannot be undone!');
        if (confirmation) {
            const queryParts = [];
            bulkSelected.forEach((file) => {
                const safeFileName = encodeURIComponent(file);
                queryParts.push(`files[]=${safeFileName}`);
            });
            window.location.replace(`delete_files.php?${queryParts.join('&')}`);
        }
    });

    $('#bulk-download').on('click', () => {
        const queryParts = [];
        bulkSelected.forEach((file) => {
            const safeFileName = encodeURIComponent(file);
            queryParts.push(`files[]=${safeFileName}`);
        });
        window.location.replace(`generate_zip_of_files.php?${queryParts.join('&')}`);
    });

    $('.bi-box-arrow-right').on('click', () => {
        window.location.replace('logout.php');
    });

    $('.bi-share-fill').on('click', () => {
        window.location.replace('generate_custom_uploader_file.php');
    });

    $('.bi-file-zip-fill').on('click', () => {
        window.location.replace('generate_zip_of_files.php');
    });

    $('.bi-trash-fill').on('click', () => {
        const confirmation = confirm('Are you sure you want to permanently delete all uploads? This action cannot be undone!');
        if (confirmation) {
            window.location.replace('delete_files.php');
        }
    });

    $('.bi-terminal-fill').on('click', () => {
        $('#uploader-script-modal').modal('show');
    });

    $('#text-upload-mode').on('click', () => {
        $('#text-upload-mode').addClass('active');
        $('#file-upload-mode').removeClass('active');
        $('#text-uploader').addClass('d-flex');
        $('#text-uploader').removeClass('d-none');
        $('#gallery-uploader').addClass('d-none');
        $('#gallery-uploader').removeClass('d-flex');
    });

    $('#file-upload-mode').on('click', () => {
        $('#file-upload-mode').addClass('active');
        $('#text-upload-mode').removeClass('active');
        $('#gallery-uploader').addClass('d-flex');
        $('#gallery-uploader').removeClass('d-none');
        $('#text-uploader').addClass('d-none');
        $('#text-uploader').removeClass('d-flex');
    });

    $('textarea').keydown(function(e) {
        if (e.keyCode === 9) {
            e.preventDefault();
            const tabChars = '    ';
            const startPos = this.selectionStart;
            const endPos = this.selectionEnd;
            this.value = this.value.substring(0, this.selectionStart)
                + tabChars
                + this.value.substring(this.selectionEnd);
            this.focus();
            this.selectionStart = startPos + tabChars.length;
            this.selectionEnd = startPos + tabChars.length;
        }
    });

    $('.rename-button').on('click', (e) => {
        const fileName = $(e.target).parent().parent().data('filename');
        if (fileName) {
            const newName = prompt(`What would you like to rename ${fileName} to?`);
            if (newName === null) return;
            if (newName !== '') {
                window.location.href = `rename_file.php?oldname=${encodeURIComponent(fileName)}&newname=${encodeURIComponent(newName)}`;
            } else {
                alert('File name cannot be empty.');
            }
        }
    });

    const dateFormat = $('body').data('dateformat') || 'MMMM Do YYYY, HH:mm:ss';

    $('#file-table').DataTable({
        'order': [[ 2, 'desc' ]],
        'columnDefs': [
            { 'orderable': false, 'targets': 4 },
            { 'render': $.fn.dataTable.render.moment('X', dateFormat), 'targets': 2 },
            { 'responsivePriority': 1, 'targets': 0 },
            { 'responsivePriority': 4, 'targets': 1 },
            { 'responsivePriority': 3, 'targets': 2 },
            { 'responsivePriority': 5, 'targets': 3 },
            { 'responsivePriority': 2, 'targets': 4 },
            { 'type': 'file-size', 'targets': 1 }
        ],
        'language': {
            'emptyTable': 'You have not uploaded any files yet.<br><br>Press the Share icon below to download a ShareX custom uploader configuration file.<br>You can also upload files directly in the box above.',
        },
    });
    $('div.alert').delay(5000).fadeOut(500);
});
