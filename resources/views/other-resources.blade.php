@extends('layouts.site')

@section('title', 'SDA CHURCH MUBS | Other Resources')

@section('content')
    <style>
        .resources-page {
            display: grid;
            gap: 1rem;
        }

        .resources-heading {
            margin: 0;
            font-size: clamp(1.4rem, 3vw, 2rem);
            line-height: 1.2;
            color: #ffffff;
            background: var(--footer-bg);
            border-radius: 8px;
            padding: 0.55rem 0.7rem;
            text-transform: uppercase;
        }

        .resources-lead {
            margin: 0;
            color: #2f3d57;
            line-height: 1.55;
            max-width: 1020px;
        }


        .resources-layout {
            display: grid;
            grid-template-columns: minmax(0, 1.65fr) minmax(280px, 1fr);
            gap: 1rem;
            align-items: start;
        }

        .resource-browser,
        .resource-preview {
            background: transparent;
            border: 0;
            border-radius: 0;
            padding: 0;
            box-shadow: none;
        }

        .resource-preview {
            position: sticky;
            top: 1rem;
            display: grid;
            gap: 0.65rem;
        }

        .resource-preview h2 {
            margin: 0;
            color: #0f2b55;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .resource-preview-subtitle {
            margin: 0;
            color: #50617b;
            font-size: 0.9rem;
        }

        .resource-preview-frame {
            width: 100%;
            min-height: 600px;
            border: 1px solid #d7e1ee;
            border-radius: 6px;
            background: #ffffff;
        }

        .resource-preview-shell {
            position: relative;
            display: grid;
            gap: 0.45rem;
        }

        .resource-preview-open {
            position: absolute;
            right: 0.65rem;
            top: 0.65rem;
            z-index: 2;
            border: 1px solid #cfdcf0;
            background: rgba(255, 255, 255, 0.95);
            color: #1f4a8a;
            border-radius: 999px;
            padding: 0.35rem 0.65rem;
            font-weight: 700;
            font-size: 0.8rem;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(15, 43, 85, 0.14);
        }

        .resource-preview-open:hover {
            background: #ffffff;
            border-color: #aec4e5;
        }

        .resource-preview-tip {
            margin: 0;
            color: #586d8d;
            font-size: 0.82rem;
            font-weight: 600;
        }

        .resource-zoom-modal {
            position: fixed;
            inset: 0;
            z-index: 260;
            background: rgba(7, 20, 40, 0.78);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .resource-zoom-modal.show {
            display: flex;
        }

        .resource-zoom-dialog {
            width: min(1240px, 98vw);
            height: min(900px, 95vh);
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #d6e0ef;
            box-shadow: 0 24px 48px rgba(7, 20, 40, 0.3);
            display: grid;
            grid-template-rows: auto 1fr;
            overflow: hidden;
        }

        .resource-zoom-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.65rem;
            padding: 0.65rem 0.75rem;
            border-bottom: 1px solid #dde5f2;
            background: #f5f8fe;
        }

        .resource-zoom-title {
            margin: 0;
            color: #1b3d6d;
            font-size: 0.92rem;
            font-weight: 700;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .resource-zoom-actions {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .resource-zoom-btn {
            border: 1px solid #cddaf0;
            background: #ffffff;
            color: #1f4a8a;
            border-radius: 8px;
            padding: 0.33rem 0.58rem;
            font-weight: 700;
            cursor: pointer;
            font-size: 0.8rem;
            line-height: 1;
        }

        .resource-zoom-btn:hover {
            background: #ecf2ff;
            border-color: #b7c9e8;
        }

        .resource-zoom-level {
            margin: 0;
            min-width: 62px;
            text-align: center;
            color: #35557f;
            font-size: 0.82rem;
            font-weight: 700;
        }

        .resource-zoom-body {
            background: #edf3fb;
            overflow: auto;
            padding: 0.75rem;
        }

        .resource-zoom-canvas {
            width: 100%;
            min-height: 100%;
            transform-origin: top left;
        }

        .resource-zoom-frame {
            width: 100%;
            height: calc(min(900px, 95vh) - 130px);
            min-height: 620px;
            border: 1px solid #d4deee;
            border-radius: 10px;
            background: #ffffff;
            transform-origin: top left;
        }

        .resource-preview-empty {
            margin: 0;
            padding: 0.7rem 0;
            border: 0;
            border-top: 1px solid #d9e0ec;
            border-bottom: 1px solid #d9e0ec;
            color: #49607f;
            background: transparent;
            line-height: 1.5;
            font-weight: 600;
        }

        .resource-status {
            margin: 0;
            font-size: 0.84rem;
            font-weight: 700;
            color: #30527d;
            letter-spacing: 0.02em;
        }

        .resource-category {
            border: 1px solid #d8e3f2;
            border-radius: 12px;
            background: linear-gradient(180deg, #ffffff 0%, #f6f9ff 100%);
            overflow: hidden;
            box-shadow: 0 10px 22px rgba(15, 43, 85, 0.08);
        }

        .resource-category + .resource-category {
            margin-top: 0.8rem;
        }

        .resource-category-toggle {
            width: 100%;
            border: 0;
            background: linear-gradient(135deg, #3f8796 0%, #4e9aaa 100%);
            color: #ffffff;
            padding: 0.75rem 0.9rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.7rem;
            text-align: left;
            cursor: pointer;
        }

        .resource-category-heading {
            margin: 0;
            color: #ffffff;
            font-size: clamp(1.05rem, 2.3vw, 1.3rem);
            line-height: 1.2;
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal;
        }

        .resource-category-meta {
            margin: 0.12rem 0 0;
            font-size: 0.78rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.86);
            font-weight: 700;
        }

        .resource-category-caret {
            font-size: 1rem;
            color: #ffffff;
            transition: transform 0.2s ease;
        }

        .resource-category.is-open .resource-category-caret {
            transform: rotate(180deg);
        }

        .resource-category-panel {
            display: none;
            padding: 0.9rem;
        }

        .resource-category.is-open .resource-category-panel {
            display: block;
        }

        .resource-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 0.8rem;
        }

        .resource-card {
            border: 1px solid #d6e2f2;
            border-radius: 10px;
            background: #ffffff;
            padding: 0.75rem;
            box-shadow: 0 6px 16px rgba(15, 43, 85, 0.06);
            display: grid;
            gap: 0.65rem;
        }

        .resource-card h3 {
            margin: 0;
            color: #0f2b55;
            font-size: 1.03rem;
            line-height: 1.25;
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal;
        }

        .resource-card p {
            margin: 0;
            color: #3d516f;
            line-height: 1.48;
            font-size: 0.9rem;
        }

        .resource-card-actions {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.5rem;
        }

        .resource-card-actions .resource-action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #c9d9ee;
            background: #f5f9ff;
            color: #17406f;
            border-radius: 12px;
            min-height: 2.35rem;
            padding: 0.42rem 0.7rem;
            font-weight: 700;
            font-size: 0.83rem;
            text-decoration: none;
            cursor: pointer;
            line-height: 1.2;
            text-align: center;
            transition: background-color 0.2s ease, border-color 0.2s ease, transform 0.18s ease, box-shadow 0.2s ease;
        }

        .resource-card-actions .resource-action-btn:hover,
        .resource-card-actions .resource-action-btn:focus-visible {
            background: #eaf2ff;
            border-color: #aec5e6;
            box-shadow: 0 7px 14px rgba(15, 43, 85, 0.14);
            transform: translateY(-1px);
            outline: none;
        }

        .resource-card-actions .resource-action-primary {
            grid-column: 1 / -1;
            border-color: #153f75;
            background: linear-gradient(135deg, #0f2b55 0%, #1f4a8a 100%);
            color: #ffffff;
        }

        .resource-card-actions .resource-action-primary:hover,
        .resource-card-actions .resource-action-primary:focus-visible {
            background: linear-gradient(135deg, #123060 0%, #295698 100%);
            border-color: #123969;
            box-shadow: 0 10px 18px rgba(15, 43, 85, 0.22);
        }

        .resource-card-actions .resource-action-download {
            border-color: #b6cfec;
            background: #edf5ff;
            color: #153b67;
        }

        .resource-card-actions .resource-action-read {
            border-color: #d2ddea;
            background: #ffffff;
            color: #275282;
        }

        .resource-lessons-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            border: 1px solid #d1deef;
            border-radius: 8px;
            background: #ffffff;
            color: #1b4677;
            padding: 0.45rem 0.6rem;
            font-weight: 700;
            cursor: pointer;
            text-align: left;
        }

        .resource-lessons-toggle:hover,
        .resource-lessons-toggle:focus-visible {
            background: #f1f6ff;
            border-color: #b3c8e8;
            outline: none;
        }

        .resource-lessons-accordion {
            margin-top: 0.5rem;
        }

        .resource-lessons-accordion > summary {
            list-style: none;
        }

        .resource-lessons-accordion > summary::-webkit-details-marker {
            display: none;
        }

        .resource-lessons-caret {
            transition: transform 0.2s ease;
        }

        .resource-lessons-toggle[aria-expanded='true'] .resource-lessons-caret {
            transform: rotate(180deg);
        }

        .resource-lessons-accordion[open] .resource-lessons-caret {
            transform: rotate(180deg);
        }

        .resource-lessons {
            margin-top: 0.5rem;
            display: none;
            gap: 0.35rem;
        }

        .resource-lessons-toggle[aria-expanded='true'] ~ .resource-lessons {
            display: grid;
        }

        .resource-lesson-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            width: 100%;
            padding: 0.42rem 0.5rem;
            border: 1px solid #d7e2f0;
            border-radius: 7px;
            background: #fbfdff;
            color: #204672;
            font-weight: 700;
            cursor: pointer;
            text-align: left;
        }

        .resource-lesson-btn:hover,
        .resource-lesson-btn:focus-visible {
            background: #eef4ff;
            border-color: #b8cde9;
            outline: none;
        }

        .resource-lesson-hint {
            color: #53729a;
            font-weight: 700;
            font-size: 0.72rem;
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }

        @media (max-width: 980px) {
            .resources-layout {
                grid-template-columns: 1fr;
            }

            .resource-preview {
                position: static;
            }

            .resource-preview-frame {
                min-height: 420px;
            }

            .resource-zoom-frame {
                min-height: 460px;
            }
        }

        @media (max-width: 640px) {
            .resources-page {
                gap: 0.85rem;
            }

            .resources-heading {
                padding: 0.5rem 0.58rem;
            }

            .resource-browser,
            .resource-preview {
                padding: 0;
                border-radius: 0;
            }

            .resource-category-toggle {
                padding: 0.65rem 0.68rem;
            }

            .resource-category-panel {
                padding: 0.65rem;
            }

            .resource-grid {
                grid-template-columns: 1fr;
            }

            .resource-card {
                padding: 0.62rem;
            }

            .resource-card-actions a,
            .resource-card-actions .resource-action-btn {
                font-size: 0.78rem;
            }

            .resource-lesson-btn {
                padding: 0.45rem 0.5rem;
                font-size: 0.86rem;
            }

            .resource-preview-frame {
                min-height: 320px;
            }

            .resource-preview-open {
                top: 0.5rem;
                right: 0.5rem;
            }

            .resource-zoom-modal {
                padding: 0.4rem;
            }

            .resource-zoom-dialog {
                width: 100%;
                height: 96vh;
            }

            .resource-zoom-head {
                padding: 0.55rem;
                align-items: flex-start;
            }

            .resource-zoom-title {
                white-space: normal;
                line-height: 1.3;
            }

            .resource-zoom-actions {
                gap: 0.28rem;
            }

            .resource-zoom-btn {
                padding: 0.32rem 0.5rem;
                font-size: 0.77rem;
            }

            .resource-zoom-frame {
                height: calc(96vh - 142px);
                min-height: 420px;
            }

            .resource-preview-frame {
                min-height: 240px;
            }

            .resource-card-actions {
                gap: 0.42rem;
                grid-template-columns: 1fr;
            }

            .resource-card-actions .resource-action-btn {
                min-height: 2.2rem;
                font-size: 0.8rem;
            }

            .resource-card-actions .resource-action-primary {
                grid-column: auto;
            }
        }
    </style>

    <section class="resources-page" aria-label="Other resources and study guides">
        <h1 class="resources-heading">Other Resources</h1>
        <p class="resources-lead">
           Find more topics about Adventist beliefs, lifestyle, and mission in these study guides. Each guide is packed with insights, activities, and inspiration to help you grow in faith and understanding. Browse the categories below to explore the full collection of downloadable PDFs for individual lessons or complete courses.
        </p>
          <p id="resourceStatus" class="resource-status" aria-live="polite" hidden></p>

        <div class="resources-layout">
            <div id="resourceCategories" class="resource-browser" aria-label="Study guide categories"></div>
            <aside class="resource-preview" aria-label="Study guide preview">
                <h2>Guide Preview</h2>
                <p id="resourcePreviewTitle" class="resource-preview-subtitle">Select any lesson or full course PDF to preview it here.</p>
                <p id="resourcePreviewEmpty" class="resource-preview-empty">No guide selected yet.</p>
                <div id="resourcePreviewShell" class="resource-preview-shell" hidden>
                    <button id="resourcePreviewOpenBtn" class="resource-preview-open" type="button">Open Zoom Reader</button>
                    <iframe id="resourcePreviewFrame" class="resource-preview-frame" title="Study guide preview" loading="eager"></iframe>
                    <p class="resource-preview-tip">Click “Open Zoom Reader” for adjustable zoom and better reading.</p>
                </div>
            </aside>
        </div>

        <div id="resourceZoomModal" class="resource-zoom-modal" aria-hidden="true">
            <div class="resource-zoom-dialog" role="dialog" aria-modal="true" aria-labelledby="resourceZoomTitle">
                <div class="resource-zoom-head">
                    <p id="resourceZoomTitle" class="resource-zoom-title">Guide Reader</p>
                    <div class="resource-zoom-actions">
                        <button id="resourceZoomOut" class="resource-zoom-btn" type="button" aria-label="Zoom out">−</button>
                        <p id="resourceZoomLevel" class="resource-zoom-level">100%</p>
                        <button id="resourceZoomIn" class="resource-zoom-btn" type="button" aria-label="Zoom in">+</button>
                        <button id="resourceZoomReset" class="resource-zoom-btn" type="button">Reset</button>
                        <button id="resourceZoomFit" class="resource-zoom-btn" type="button">Fit Width</button>
                        <button id="resourceZoomClose" class="resource-zoom-btn" type="button">Close</button>
                    </div>
                </div>
                <div class="resource-zoom-body">
                    <div id="resourceZoomCanvas" class="resource-zoom-canvas">
                        <iframe id="resourceZoomFrame" class="resource-zoom-frame" title="Zoomable study guide preview" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="{{ asset('courses2.js') }}"></script>
    <script>
        (function () {
            const status = document.getElementById('resourceStatus');
            const wrapper = document.getElementById('resourceCategories');
            const previewTitle = document.getElementById('resourcePreviewTitle');
            const previewEmpty = document.getElementById('resourcePreviewEmpty');
            const previewShell = document.getElementById('resourcePreviewShell');
            const previewFrame = document.getElementById('resourcePreviewFrame');
            const previewOpenBtn = document.getElementById('resourcePreviewOpenBtn');
            const zoomModal = document.getElementById('resourceZoomModal');
            const zoomFrame = document.getElementById('resourceZoomFrame');
            const zoomCanvas = document.getElementById('resourceZoomCanvas');
            const zoomLevel = document.getElementById('resourceZoomLevel');
            const zoomTitle = document.getElementById('resourceZoomTitle');
            const zoomInBtn = document.getElementById('resourceZoomIn');
            const zoomOutBtn = document.getElementById('resourceZoomOut');
            const zoomResetBtn = document.getElementById('resourceZoomReset');
            const zoomFitBtn = document.getElementById('resourceZoomFit');
            const zoomCloseBtn = document.getElementById('resourceZoomClose');
            const zoomBody = document.querySelector('.resource-zoom-body');
            const basePath = 'https://s3.ap-southeast-2.amazonaws.com/files.adventistmedia.org.au/resources/course-booklets/';
            const categoryData = Array.isArray(window.categories)
                ? window.categories
                : (typeof categories !== 'undefined' && Array.isArray(categories) ? categories : []);

            let activePreviewUrl = '';
            let activePreviewEmbedUrl = '';
            let activePreviewLabel = '';
            let zoomScale = 1;

            const isSmallViewport = function () {
                return window.matchMedia('(max-width: 980px)').matches;
            };

            const isPdfUrl = function (url) {
                const cleanUrl = String(url || '').trim();
                if (cleanUrl === '') {
                    return false;
                }

                return /\.pdf([?#].*)?$/i.test(cleanUrl);
            };

            const toInlineViewerUrl = function (url) {
                const cleanUrl = String(url || '').trim();
                if (cleanUrl === '') {
                    return '';
                }

                if (!isPdfUrl(cleanUrl)) {
                    return '';
                }

                // Use direct PDF display instead of Google Docs viewer
                // Add #view=FitH parameter to fit width
                return cleanUrl + '#view=FitH';
            };

            const getCoursePackUrl = function (course) {
                const uri = String((course && course.uri) || '').trim();
                if (uri === '') {
                    return '';
                }

                return basePath + uri;
            };

            const getCoursePreviewUrl = function (course, lessons) {
                const packUrl = getCoursePackUrl(course);

                if (isPdfUrl(packUrl)) {
                    return packUrl;
                }

                if (Array.isArray(lessons) && lessons.length > 0) {
                    const firstLessonUri = String((lessons[0] && lessons[0].uri) || '').trim();
                    if (firstLessonUri !== '') {
                        return basePath + firstLessonUri;
                    }
                }

                return '';
            };

            if (!wrapper || !previewTitle || !previewEmpty || !previewShell || !previewFrame || !previewOpenBtn || !zoomModal || !zoomFrame || !zoomCanvas || !zoomLevel || !zoomTitle || !zoomInBtn || !zoomOutBtn || !zoomResetBtn || !zoomFitBtn || !zoomCloseBtn || !zoomBody) {
                return;
            }

            if (!Array.isArray(categoryData) || categoryData.length === 0) {
                if (status) {
                    status.hidden = false;
                    status.textContent = 'Unable to load study-guide content right now.';
                }
                return;
            }

            const updateZoomUi = function () {
                const percent = Math.round(zoomScale * 100);
                zoomLevel.textContent = percent + '%';
                zoomFrame.style.transform = 'scale(' + zoomScale + ')';
                zoomCanvas.style.width = (100 / zoomScale) + '%';
            };

            const openZoomReader = function () {
                if (!activePreviewUrl) {
                    return;
                }

                zoomTitle.textContent = activePreviewLabel || 'Guide Reader';
                zoomFrame.src = activePreviewEmbedUrl;
                zoomScale = 1;
                updateZoomUi();
                zoomModal.classList.add('show');
                zoomModal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            };

            const closeZoomReader = function () {
                zoomModal.classList.remove('show');
                zoomModal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            };

            const openPreview = function (url, label) {
                if (!url) {
                    return;
                }

                activePreviewUrl = url;
                activePreviewEmbedUrl = toInlineViewerUrl(url);
                if (activePreviewEmbedUrl === '') {
                    return;
                }
                activePreviewLabel = label;
                previewShell.hidden = false;
                previewEmpty.hidden = true;
                previewTitle.textContent = label;
                
                // Load iframe source with a small delay to prevent blocking other interactions
                requestAnimationFrame(function() {
                    previewFrame.src = activePreviewEmbedUrl;
                });

                if (isSmallViewport()) {
                    previewShell.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            };

            previewOpenBtn.addEventListener('click', openZoomReader);
            previewFrame.addEventListener('click', openZoomReader);

            zoomInBtn.addEventListener('click', function () {
                zoomScale = Math.min(2.5, zoomScale + 0.2);
                updateZoomUi();
            });

            zoomOutBtn.addEventListener('click', function () {
                zoomScale = Math.max(0.6, zoomScale - 0.2);
                updateZoomUi();
            });

            zoomResetBtn.addEventListener('click', function () {
                zoomScale = 1;
                updateZoomUi();
            });

            zoomFitBtn.addEventListener('click', function () {
                zoomScale = 1.2;
                updateZoomUi();
            });

            zoomCloseBtn.addEventListener('click', closeZoomReader);

            zoomBody.addEventListener('wheel', function (event) {
                if (!zoomModal.classList.contains('show') || !event.ctrlKey) {
                    return;
                }

                event.preventDefault();

                if (event.deltaY < 0) {
                    zoomScale = Math.min(2.5, zoomScale + 0.08);
                } else {
                    zoomScale = Math.max(0.6, zoomScale - 0.08);
                }

                updateZoomUi();
            }, { passive: false });

            zoomModal.addEventListener('click', function (event) {
                if (event.target === zoomModal) {
                    closeZoomReader();
                }
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeZoomReader();
                }
            });

            let totalCourses = 0;
            let totalLessons = 0;

            categoryData.forEach(function (category, categoryIndex) {
                const categorySection = document.createElement('section');
                categorySection.className = 'resource-category';

                const categoryCourses = Array.isArray(category.courses) ? category.courses : [];
                totalCourses += categoryCourses.length;

                const categoryToggle = document.createElement('button');
                categoryToggle.type = 'button';
                categoryToggle.className = 'resource-category-toggle';
                categoryToggle.setAttribute('aria-expanded', categoryIndex === 0 ? 'true' : 'false');

                const categoryHeadingWrap = document.createElement('span');

                const categoryTitle = document.createElement('h2');
                categoryTitle.className = 'resource-category-heading';
                categoryTitle.textContent = category.title || 'Category';
                categoryHeadingWrap.appendChild(categoryTitle);

                const categoryCount = document.createElement('p');
                categoryCount.className = 'resource-category-meta';
                categoryCount.textContent = categoryCourses.length + ' course(s)';
                categoryHeadingWrap.appendChild(categoryCount);

                categoryToggle.appendChild(categoryHeadingWrap);

                const categoryCaret = document.createElement('span');
                categoryCaret.className = 'resource-category-caret';
                categoryCaret.setAttribute('aria-hidden', 'true');
                categoryCaret.textContent = '▾';
                categoryToggle.appendChild(categoryCaret);

                categorySection.appendChild(categoryToggle);

                const categoryPanel = document.createElement('div');
                categoryPanel.className = 'resource-category-panel';

                const categoryGrid = document.createElement('div');
                categoryGrid.className = 'resource-grid';

                categoryCourses.forEach(function (course) {
                    const title = course.title || 'Course';
                    const description = course.description || 'Course guide resources.';
                    const packUrl = getCoursePackUrl(course);
                    const lessons = Array.isArray(course.lessons) ? course.lessons.filter(function (lesson) {
                        return lesson && lesson.uri;
                    }) : [];
                    const previewUrl = getCoursePreviewUrl(course, lessons);

                    totalLessons += lessons.length;

                    const card = document.createElement('article');
                    card.className = 'resource-card';

                    const cardTitle = document.createElement('h3');
                    cardTitle.textContent = title;
                    card.appendChild(cardTitle);

                    const cardText = document.createElement('p');
                    cardText.textContent = description;
                    card.appendChild(cardText);

                    if (lessons.length > 0) {
                        const lessonsHeader = document.createElement('button');
                        lessonsHeader.type = 'button';
                        lessonsHeader.className = 'resource-lessons-toggle';
                        lessonsHeader.setAttribute('role', 'heading');
                        lessonsHeader.setAttribute('aria-level', '4');
                        lessonsHeader.setAttribute('aria-expanded', 'false');
                        lessonsHeader.innerHTML = '<span>Lessons (' + lessons.length + ')</span><span class="resource-lessons-caret" aria-hidden="true">▾</span>';
                        
                        lessonsHeader.addEventListener('click', function (e) {
                            e.preventDefault();
                            const isExpanded = lessonsHeader.getAttribute('aria-expanded') === 'true';
                            lessonsHeader.setAttribute('aria-expanded', !isExpanded);
                        });

                        const lessonsWrap = document.createElement('div');
                        lessonsWrap.className = 'resource-lessons';

                        lessons.forEach(function (lesson, lessonIndex) {
                            const lessonTitle = String((lesson && lesson.title) || ('Lesson ' + (lessonIndex + 1))).trim();
                            const lessonUri = String((lesson && lesson.uri) || '').trim();

                            if (lessonUri === '') {
                                return;
                            }

                            const lessonPreviewUrl = basePath + lessonUri;
                            const lessonBtn = document.createElement('button');
                            lessonBtn.type = 'button';
                            lessonBtn.className = 'resource-lesson-btn';
                            lessonBtn.innerHTML = '<span>' + lessonTitle + '</span><span class="resource-lesson-hint">Preview</span>';
                            lessonBtn.addEventListener('click', function () {
                                openPreview(lessonPreviewUrl, title + ' · ' + lessonTitle);
                            });

                            lessonsWrap.appendChild(lessonBtn);
                        });

                        card.appendChild(lessonsHeader);
                        card.appendChild(lessonsWrap);
                    }

                    // Modern action button layout with icons
                    const actions = document.createElement('div');
                    actions.className = 'resource-card-actions';

                    if (previewUrl) {
                        const previewAll = document.createElement('button');
                        previewAll.type = 'button';
                        previewAll.className = 'resource-action-btn resource-action-primary';
                        previewAll.innerHTML = '<span class="resource-action-icon" aria-hidden="true">🔎</span> <span>Preview First Lesson</span>';
                        previewAll.addEventListener('click', function () {
                            openPreview(previewUrl, title + ' · Preview');
                        });
                        actions.appendChild(previewAll);

                        const readAll = document.createElement('a');
                        readAll.href = previewUrl;
                        readAll.target = '_blank';
                        readAll.rel = 'noopener';
                        readAll.className = 'resource-action-btn resource-action-read';
                        readAll.innerHTML = '<span class="resource-action-icon" aria-hidden="true">📖</span> <span>Read PDF</span>';
                        actions.appendChild(readAll);
                    }

                    if (packUrl) {
                        const downloadAll = document.createElement('a');
                        downloadAll.href = packUrl;
                        downloadAll.target = '_blank';
                        downloadAll.rel = 'noopener';
                        downloadAll.className = 'resource-action-btn resource-action-download';
                        downloadAll.innerHTML = '<span class="resource-action-icon" aria-hidden="true">⬇️</span> <span>' + (isPdfUrl(packUrl) ? 'Download Full Course' : 'Download Course Pack') + '</span>';
                        actions.appendChild(downloadAll);
                    }

                    if (actions.childElementCount > 0) {
                        card.appendChild(actions);
                    }

                    categoryGrid.appendChild(card);
                });

                categoryPanel.appendChild(categoryGrid);
                categorySection.appendChild(categoryPanel);

                if (categoryIndex === 0) {
                    categorySection.classList.add('is-open');
                }

                categoryToggle.addEventListener('click', function () {
                    const shouldOpen = !categorySection.classList.contains('is-open');
                    categorySection.classList.toggle('is-open', shouldOpen);
                    categoryToggle.setAttribute('aria-expanded', shouldOpen ? 'true' : 'false');
                });

                wrapper.appendChild(categorySection);
            });

            if (status) {
                status.hidden = true;
                status.textContent = '';
            }
        })();
    </script>
@endsection

