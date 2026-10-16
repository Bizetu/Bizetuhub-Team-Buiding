@extends('layouts.twill.users')

@push('extra_css')
    <link href="{{ asset('css/reports.css') }}" rel="stylesheet">
    <link href="{{ asset('css/twill/main.css') }}" rel="stylesheet">
@endpush

@section('customPageContent')
    <div class="container-fluid row">
        <div class="upload-container col-12 col-lg-6">
            <p class="ut">Archive User(s)</p>
            <form method="post" action="{{ route('twill.archive.users') }}" enctype="multipart/form-data" class="upload-form" id="archiveForm">
                @csrf

                <div class="upload-body">
                    <div class="file-input-group">
                        <label class="file-input-label" for="file">
                            Upload excel file with emails of users to be archived
                        </label>

                        <div class="file-input-wrapper">
                            <input
                                type="file"
                                name="file"
                                id="file"
                                class="file-input"
                                accept=".xlsx,.xls"
                                required
                            >
                            <div class="file-input-display" id="archiveFileDisplay">
                                <span class="file-icon"><i class="fa fa-file" aria-hidden="true"></i></span>
                                <span class="file-text" id="archiveFileText">Upload Excel file</span>
                            </div>
                        </div>

                        <div class="mb-3 pt-2">
                            <a href="{{ route('twill.archive.template') }}" class="template-download">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                                Download Template
                            </a>
                        </div>

                        <div class="file-requirements pt-2">
                            <strong>Requirements:</strong><br>
                            • File types: Excel (.xlsx, .xls)<br>
                            • Maximum file size: 2 MB<br>
                            • Should only contain <b>'email'</b> as the first column containing user emails
                        </div>

                        <div class="error-message" id="archiveErrorMessage"></div>
                    </div>
                    <button type="submit" class="button shadow-btn" id="archiveSubmitBtn" disabled>Archive User(s)</button>
                </div>
            </form>
        </div>

        <div class="upload-container col-12 col-lg-6">
            <p class="ut">Unarchive User(s)</p>
            <form
                method="post"
                action="{{ route('twill.archive.restore') }}"
                enctype="multipart/form-data"
                class="upload-form"
                id="restoreForm"
            >
                @csrf

                <div class="upload-body">
                    <div class="file-input-group">
                        <label class="file-input-label" for="restore_file">
                            Upload Excel with emails of users to be un-archived
                        </label>

                        <div class="file-input-wrapper">
                            <input
                                type="file"
                                name="restore_file"
                                id="restore_file"
                                class="file-input"
                                accept=".xlsx,.xls"
                                required
                            >
                            <div class="file-input-display" id="restoreFileDisplay">
                                <span class="file-icon"><i class="fa fa-file" aria-hidden="true"></i></span>
                                <span class="file-text" id="restoreFileText">Upload Excel file</span>
                            </div>
                        </div>

                        <div class="mb-3 pt-2">
                            <a href="{{ route('twill.archive.template') }}" class="template-download">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                                Download Template
                            </a>
                        </div>

                        <div class="file-requirements pt-2">
                            <strong>Requirements:</strong><br>
                            • File types: Excel (.xlsx, .xls)<br>
                            • Maximum file size: 2 MB<br>
                            • Should only contain <b>'email'</b> as the first column containing user emails
                        </div>

                        <div class="error-message" id="restoreErrorMessage"></div>
                    </div>
                    <button type="submit" class="button shadow-btn" id="restoreSubmitBtn" disabled>Restore User(s)</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Archive form elements
            const fileInput = document.getElementById('file');
            const archiveFileDisplay = document.getElementById('archiveFileDisplay');
            const archiveFileText = document.getElementById('archiveFileText');
            const archiveErrorMessage = document.getElementById('archiveErrorMessage');
            const archiveSubmitBtn = document.getElementById('archiveSubmitBtn');
            const archiveForm = document.getElementById('archiveForm');

            // Restore form elements
            const restoreFileInput = document.getElementById('restore_file');
            const restoreFileDisplay = document.getElementById('restoreFileDisplay');
            const restoreFileText = document.getElementById('restoreFileText');
            const restoreErrorMessage = document.getElementById('restoreErrorMessage');
            const restoreSubmitBtn = document.getElementById('restoreSubmitBtn');
            const restoreForm = document.getElementById('restoreForm');

            const allowedTypes = ['.xlsx', '.xls'];
            const maxSizeBytes = 2 * 1024 * 1024; // 2MB

            function showError(errorElement, displayElement, message) {
                errorElement.textContent = message;
                errorElement.classList.add('show');
                displayElement.classList.add('has-error');
                displayElement.classList.remove('has-file');
            }

            function clearError(errorElement, displayElement) {
                errorElement.classList.remove('show');
                displayElement.classList.remove('has-error');
            }

            function validateFile(file) {
                if (!file) {
                    return { valid: false, message: 'Please select a file' };
                }

                // Check file type
                const fileName = file.name.toLowerCase();
                const isValidType = allowedTypes.some(type => fileName.endsWith(type));

                if (!isValidType) {
                    return { valid: false, message: 'Invalid file type. Please select Excel file (.xlsx, .xls)' };
                }

                // Check file size
                if (file.size > maxSizeBytes) {
                    return { valid: false, message: 'File size exceeds 2MB limit. Please choose a smaller file.' };
                }

                return { valid: true };
            }

            // Archive form functionality
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];

                clearError(archiveErrorMessage, archiveFileDisplay);

                if (!file) {
                    archiveFileText.textContent = 'Choose Excel file...';
                    archiveFileText.classList.remove('has-file');
                    archiveFileDisplay.classList.remove('has-file');
                    archiveSubmitBtn.disabled = true;
                    return;
                }

                const validation = validateFile(file);
                if (validation.valid) {
                    archiveFileText.textContent = file.name;
                    archiveFileText.classList.add('has-file');
                    archiveFileDisplay.classList.add('has-file');
                    archiveSubmitBtn.disabled = false;
                } else {
                    showError(archiveErrorMessage, archiveFileDisplay, validation.message);
                    archiveFileText.textContent = 'Choose Excel file...';
                    archiveFileText.classList.remove('has-file');
                    archiveSubmitBtn.disabled = true;
                    fileInput.value = ''; // Clear invalid file
                }
            });

            // Restore form functionality
            restoreFileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];

                clearError(restoreErrorMessage, restoreFileDisplay);

                if (!file) {
                    restoreFileText.textContent = 'Choose Excel file...';
                    restoreFileText.classList.remove('has-file');
                    restoreFileDisplay.classList.remove('has-file');
                    restoreSubmitBtn.disabled = true;
                    return;
                }

                const validation = validateFile(file);
                if (validation.valid) {
                    restoreFileText.textContent = file.name;
                    restoreFileText.classList.add('has-file');
                    restoreFileDisplay.classList.add('has-file');
                    restoreSubmitBtn.disabled = false;
                } else {
                    showError(restoreErrorMessage, restoreFileDisplay, validation.message);
                    restoreFileText.textContent = 'Choose Excel file...';
                    restoreFileText.classList.remove('has-file');
                    restoreSubmitBtn.disabled = true;
                    restoreFileInput.value = ''; // Clear invalid file
                }
            });

            // Prevent form submission if validation fails
            archiveForm.addEventListener('submit', function(e) {
                const file = fileInput.files[0];
                const validation = validateFile(file);
                if (!validation.valid) {
                    e.preventDefault();
                    showError(archiveErrorMessage, archiveFileDisplay, validation.message);
                    return false;
                }
            });

            restoreForm.addEventListener('submit', function(e) {
                const file = restoreFileInput.files[0];
                const validation = validateFile(file);
                if (!validation.valid) {
                    e.preventDefault();
                    showError(restoreErrorMessage, restoreFileDisplay, validation.message);
                    return false;
                }
            });

            // Drag and drop functionality for archive form
            setupDragAndDrop(archiveFileDisplay, fileInput);

            // Drag and drop functionality for restore form
            setupDragAndDrop(restoreFileDisplay, restoreFileInput);

            function setupDragAndDrop(displayElement, inputElement) {
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    displayElement.addEventListener(eventName, preventDefaults, false);
                });

                function preventDefaults(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                ['dragenter', 'dragover'].forEach(eventName => {
                    displayElement.addEventListener(eventName, highlight, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    displayElement.addEventListener(eventName, unhighlight, false);
                });

                function highlight(e) {
                    displayElement.style.borderColor = '#007bff';
                    displayElement.style.background = '#f0f8ff';
                }

                function unhighlight(e) {
                    displayElement.style.borderColor = '';
                    displayElement.style.background = '';
                }

                displayElement.addEventListener('drop', handleDrop, false);

                function handleDrop(e) {
                    const dt = e.dataTransfer;
                    const files = dt.files;

                    if (files.length > 0) {
                        inputElement.files = files;
                        inputElement.dispatchEvent(new Event('change'));
                    }
                }
            }
        });
    </script>
@stop



@push('extra_js')
    <script src="{{ twillAsset('chunk-vendors.js') }}" crossorigin></script>
    <script src="{{ twillAsset('chunk-common.js') }}" crossorigin></script>
    <script src="{{ twillAsset('main-free.js') }}" crossorigin></script>
@endpush
