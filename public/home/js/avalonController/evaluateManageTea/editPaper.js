define(['evaluateManageTea/async'], function (async) {

    var vm = avalon.define({
        $id: 'addPaper',
        quesStyle: 1,
        title: '默认试卷标题',
        taskQues: [],
        testQues: [],
        editShow: '',
        editing: undefined,
        showAnswer: null,
        window: null,
        editFocus: false,
        focus: false,
        selection: null,
        uploadStatus: 0,
        paperType: { 1: null, 2: null, 3: null, 4: null, 5: null },
        importWindow: null,
        importQues: null,
        importDifficult: 3,
        importType: 1,
        importPage: 1,
        importQuesCount: 0,
        importing: false,
        pickdate: null,
        picktime: '00:00',
        completeTime: 20,
        scoreCount: 0,
        typeCount: {},
        classInfo: [],
        selectClass: [],
        selectClassWarning: false,
        paperTitileWanring: false,
        sumitFail: false,
        userId: 1,
        gradeId: 1,
        subjectId: 1,
        bookId: 1,
        editionId: 1,
        chapterId: 1,
        importId: null,
        imgZoom: null,
        showImg: false,
        request: function (url, data, callback) {
            $.ajax({
                url: url,
                dataType: 'json',
                type: ('function' !== typeof data) ? 'POST' : 'GET',
                data: ('function' !== typeof data) ? data : null,
                success: function (response) {
                    if (response.type) {
                        ('function' === typeof data) ? data(null, response.data) : callback(null, response.data);
                    } else {
                        callback(new Error('请求失败'));
                    }
                },
                error: callback
            });
        },
        changeModel: function (action, index) {
            switch (action) {
                case 'quesStyle':
                    if (index === 2) {
                        vm.taskQues.sort(function (a, b) {
                            return a.type > b.type ? 1 : -1;
                        });
                        for (var i in vm.paperType) {
                            vm.paperType[i] = null;
                        }
                        for (i = 0; i < vm.taskQues.size(); i++) {
                            vm.taskQues[i].index = i + 1;
                            vm.taskQues.splice(i, 1, vm.taskQues[i]);
                        }
                    }
                    vm[action] = index;
                    break;

                case 'editShow':
                    vm[action] = index;
                    break;

                case 'importQues':
                    (index === 1 && vm.importQues === null) && vm.conditionChange();
                    vm.scoreCount = 0;
                    vm.importWindow = index;
                    break;

                case 'showAnswer':
                    if (vm.editing === undefined) {
                        vm[action] = index === vm[action] ? null : index;
                    }
                    break;

                case 'editing':
                    if (vm[action] === undefined || typeof index === 'string') {
                        vm[action] = (typeof index === 'string') ? undefined : index;
                    }
                    if (typeof index === 'number') {
                        vm.temp = $.extend(vm.temp, vm.taskQues[index - 1]);
                    }
                    if (index === 'cancel') {
                        vm.taskQues.set(arguments[2], vm.temp);
                        delete vm.temp;
                    }
                    vm.window = null;                                                                                                                                                                                                                                                                      break;

                case 'addQues':
                    var questions = arguments[2] || {
                            index: vm.taskQues.size() + 1,
                            type: index,
                            title: '新增题目',
                            difficult: 1,
                            choice: index < 3 ? ['默认选项', '默认选项'] : null,
                            score: 1,
                            answer: vm.defaultAnswer(index),
                            analysis: ''
                        };
                    if (vm.quesStyle === 2) {
                        var node = 0;
                        for (var i = vm.taskQues.size() - 1; i >= 0; i--) {
                            if (vm.taskQues[i].type === index || index > vm.taskQues[i].type) {
                                node = i + 1;
                                break;
                            }
                        }
                        vm.taskQues.splice(node, 0, questions);
                        for (i = 0; i < vm.taskQues.size(); i++) {
                            vm.taskQues[i].index = i + 1;
                            (vm.quesStyle === 2) && vm.taskQues.splice(i, 1, vm.taskQues[i]);
                        }
                        if (vm.quesStyle === 2) {
                            for (i in vm.paperType) {
                                vm.paperType[i] = null;
                            }
                        }
                        window.location.href = '#testQues' + node;
                    } else {
                        vm.taskQues.push(questions);
                        window.location.href = '#addQues';
                    }
                    break;

                case 'removeQues':
                    if (vm.quesStyle === 2) {
                        for (i in vm.paperType) {
                            vm.paperType[i] = null;
                        }
                    }
                    vm.taskQues.removeAt(index);
                    for (var i = 0; i < vm.taskQues.size(); i++) {
                        vm.taskQues[i].index = i + 1;
                        (vm.quesStyle === 2) && vm.taskQues.splice(i, 1, vm.taskQues[i]);
                    }
                    break;

                case 'addChoice':
                    vm.taskQues[index].choice.push('默认选项');
                    break;

                case 'removeChoice':
                    var model = vm.taskQues[arguments[2] - 1];
                    model.choice.removeAt(index);
                    if (model.type === 1 && (model.answer === String.fromCharCode(index + 65) || model.choice[model.answer.charCodeAt() - 65] === undefined)) {
                        model.answer = '';
                    }
                    if (model.type === 2) {
                        var answer = model.answer.split('┼┼');
                        (answer.length === 1 && answer[0] === '') && answer.shift();
                        (answer.indexOf(String.fromCharCode(index + 65)) >= 0) && answer.splice(answer.indexOf(String.fromCharCode(index + 65)), 1);
                        model.answer = answer.join('┼┼');
                    }
                    break;

                case 'judge':
                    vm.taskQues[index].answer = arguments[2];
                    break;

                case 'editWindow':
                    vm.window = index;
                    break;

                case 'reset':
                    if (vm.temp) {
                        vm.taskQues[index].title = vm.temp.title;
                        vm.taskQues[index].answer = vm.temp.answer;
                    }
                    break;

                case 'replaceQues':
                    if (vm.importQues && !vm.importing) {
                        vm.importTemp = $.extend(vm.importTemp, vm.importQues.$model);
                        (vm.importWindow === 1) ? ((vm.quesStyle === 1) ? vm.taskQues.push(vm.importTemp) : vm.changeModel('addQues', vm.importTemp.type, vm.importTemp)) : vm.taskQues.splice(vm.replaceIndex, 1, vm.importTemp);
                        (vm.importWindow === 2) ? vm.taskQues[vm.replaceIndex].index = vm.replaceIndex + 1 : false;
                        delete vm.importTemp;
                        (vm.importWindow === 1) ? ((vm.quesStyle === 1) ? window.location.href = '#addQues' : window.location.href = '#testQues' + vm.replaceIndex) : false;
                        vm.importWindow = null;
                    }
                    break;

                case 'replace':
                    vm.importType = index;
                    vm.replaceIndex = arguments[2];
                    vm.importWindow = 2;
                    break;
            }
        },
        moveQues: function (index, action) {
            if (action === 'up' && index !== 0 && (vm.quesStyle === 1 || vm.taskQues[index - 1].type === vm.taskQues[index].type)) {
                --vm.taskQues[index].index;
                ++vm.taskQues[index - 1].index;
                var targetElement = vm.taskQues.splice(index, 1, vm.taskQues[index - 1])[0];
                vm.taskQues.splice(index - 1, 1, targetElement);
            }

            if (action === 'down' && index !== vm.taskQues.size() - 1 && (vm.quesStyle === 1 || vm.taskQues[index + 1].type === vm.taskQues[index].type)) {
                ++vm.taskQues[index].index;
                --vm.taskQues[index + 1].index;
                var targetElement = vm.taskQues.splice(index, 1, vm.taskQues[index + 1])[0];
                vm.taskQues.splice(index + 1, 1, targetElement);
            }
        },
        requestUpload: function (fileObj, fileName, callback) {
            var formData = new FormData();
            var name = fileName.substring(fileName.lastIndexOf("\\") + 1);
            name = new Date().getTime() + "." + name.substring(name.lastIndexOf('.') + 1);
            formData.append('name', name);
            formData.append('file', fileObj);
            $.ajax({
                url: '/evaluateManageTea/uploadPaperImg',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    callback(null, response);
                },
                error: callback
            });
        },
        uploadResource: function (fileName, value) {
            vm.uploadStatus = 1;
            async.waterfall([
                function (callback) {
                    vm.requestUpload(vm.fileObj, fileName, callback);
                },
                function (result, callback) {
                    if (!result.type) {
                        return callback(new Error('上传失败'));
                    }
                    var imgPath = '<img onClick="window.addPaper.imgZoom=this.src" src="' + result.data + '" />';
                    if (typeof value[0] === 'number') {
                        if (vm.taskQues[value[0]].type === 4) {
                            vm.taskQues[value[0]][value[1]] = vm.taskQues[value[0]][value[1]].replace('[正在上传...]', '');
                            vm.taskQues[value[0]][value[1]] = vm.taskQues[value[0]][value[1]] + imgPath;
                        } else {
                            vm.taskQues[value[0]][value[1]] = vm.taskQues[value[0]][value[1]].replace('[正在上传...]', imgPath);
                        }
                        $('#' + value[1] + value[0]).html(vm.taskQues[value[0]][value[1]]);
                    } else {
                        value[0] = value[0].split('-');
                        vm.taskQues[parseInt(value[0][0])][value[1]].set(
                            parseInt(value[0][1]),
                            vm.taskQues[parseInt(value[0][0])][value[1]][parseInt(value[0][1])].replace('[正在上传...]', imgPath)
                        );
                        $('#' + value[1] + value[0][0] + '-' + value[0][1]).html(vm.taskQues[parseInt(value[0][0])][value[1]][parseInt(value[0][1])]);
                    }
                    callback(null, result);
                }
            ], function (error, result) {
                vm.uploadStatus = 0;
                (typeof value[0] === 'number') ? $('#' + value[1] + value).blur() : $('#' + value[1] + value[0][0] + '-' + value[0][1]).blur();
                if (error) {
                    if (typeof value[0] === 'number') {
                        vm.taskQues[value[0]][value[1]] = vm.taskQues[value[0]][value[1]].replace('[正在上传...]', '');
                    } else {
                        vm.taskQues[parseInt(value[0][0])][value[1]].set(
                            parseInt(value[0][1]),
                            vm.taskQues[parseInt(value[0][0])][value[1]][parseInt(value[0][1])].replace('[正在上传...]', '')
                        );
                    }
                    return vm.endUpload('上传失败');
                }
            });
        },
        defaultAnswer: function (type) {
            switch (type) {
                case 1:
                    return 'A';
                case 2:
                    return 'A┼┼B';
                case 3:
                    return 1;
                case 4:
                    return null;
                default:
                    return '默认答案';
            }
        },
        endUpload: function (tip) {
            alert(tip);
        },
        conditionChange: function (callback) {
            if (!vm.importing) {
                vm.importing = true;
                var control = [
                    function (callback) {
                        vm.request('/evaluateManageTea/importQues', { id: vm.userId, type: vm.importType, difficult: vm.importDifficult, chapterId: vm.chapterId, count: 1 }, callback);
                    },
                    function (callback) {
                        vm.request('/evaluateManageTea/importQues', { id: vm.userId, type: vm.importType, difficult: vm.importDifficult, chapterId: vm.chapterId, page: vm.importPage }, callback);
                    }
                ];
                callback ? control.shift() : vm.importPage = 1;
                async.series(control, function (err, res) {
                    vm.importing = false;
                    if (err) {
                        vm.importQuesCount = 0;
                        vm.importQues = null;
                        callback && callback(err);
                        return;
                    }
                    if (callback) {
                        res.unshift(null);
                        callback();
                    } else {
                        (vm.importQuesCount !== res[0]) ? vm.importQuesCount = res[0] : false;
                    }
                    if (res[1].choice) {
                        res[1].choice = res[1].choice.split('┼┼');
                    }
                    res[1].index = vm.taskQues.size() + 1;
                    vm.importQues = res[1];
                });
            }
        },
        publish: function () {
            if (vm.classInfo !== null && vm.classInfo.size() < 1) {
                vm.importing = true;
                vm.request('/evaluateManageTea/getTeacherClass', { id: vm.userId, subjectId: vm.subjectId, gradeId: vm.gradeId }, function (err, res) {
                    vm.importing = false;
                    vm.classInfo = err ? null : res;
                    vm.selectClass.push(vm.classInfo[0].classId);
                });
            }
            vm.typeCount = { 1: { count: 0, score: 0 }, 2: { count: 0, score: 0 }, 3: { count: 0, score: 0 }, 4: { count: 0, score: 0 }, 5: { count: 0, score: 0 } };
            vm.scoreCount = 0;
            for (var i = 0; i < vm.taskQues.size(); i++) {
                vm.scoreCount += parseInt(vm.taskQues[i].score);
                vm.typeCount[vm.taskQues[i].type].count += 1;
                vm.typeCount[vm.taskQues[i].type].score += parseInt(vm.taskQues[i].score);
            }
            vm.importWindow = 3;
        },
        submitPaper: function () {
            if (vm.importing) { return false; }
            if (vm.title === '') { window.location.href = '#paperTitle'; return vm.paperTitileWanring = true; }
            if (vm.classInfo !== null && vm.selectClass.size() < 1) { return vm.selectClassWarning = true; }
            var data = {
                paperInfo: {
                    userId: vm.userId,
                    gradeId: vm.gradeId,
                    subjectId: vm.subjectId,
                    bookId: vm.bookId,
                    editionId: vm.editionId,
                    chapterId: vm.chapterId,
                    type: 1,
                    title: vm.title,
                    count: vm.taskQues.size(),
                    score: vm.scoreCount
                },
                type: vm.quesStyle,
                submitTime: vm.pickdate + ' ' + $('#picktime').val() + ':00',
                completeTime: vm.quesStyle === 2 ? vm.completeTime : 0,
                question: vm.taskQues.$model
            };
            if (vm.selectClass) { data.dispatch = vm.selectClass; }
            vm.importing = true;
            vm.request('/evaluateManageTea/publishPaper', data, function (err, res) {
                vm.importing = false;
                if (err) {
                    vm.sumitFail = true;
                    setTimeout(function () {
                        vm.sumitFail = false;
                    }, 5000);
                } else {
                    vm.importWindow = null;
                    window.location.href = '/evaluateManageTea/index#onlineQuestion';
                }
            });
        },
        splitLessonInfo: function (info) {
            if (vm.lessonInfo) {
                vm.lessonInfo = vm.lessonInfo.split('-');
                vm.gradeId = parseInt(vm.lessonInfo[0]);
                vm.subjectId = parseInt(vm.lessonInfo[1]);
                vm.bookId = parseInt(vm.lessonInfo[2]);
                vm.editionId = parseInt(vm.lessonInfo[3]);
                vm.chapterId = parseInt(vm.lessonInfo[4]);
            }
        },
        enlarge: function (close) {
            if (vm.imgZoom) {
                if (close) {
                    vm.showImg = false;
                    vm.imgZoom = null;
                } else {
                    vm.showImg = true;
                }
            }
        },
        init: function () {
            window.addPaper = window.addPaper || vm;
            vm.splitLessonInfo();
            if (vm.importId) {
                vm.importing = true;
                vm.request('/evaluateManageTea/importPaper', { paperId: vm.importId }, function (err, res) {
                    vm.importing = false;
                    vm.importId = null;
                    vm.conditionChange();
                    if (err) {
                        return alert('试题引入失败');
                    }
                    for (var i in res) {
                        res[i].index = parseInt(res[i].index);
                        if (res[i].type < 3) {
                            res[i].choice = res[i].choice.split('┼┼');
                        }
                    }
                    vm.taskQues = res;
                    vm.taskQues.splice(vm.taskQues[0], 1, vm.taskQues[0]);
                });
            } else {
                vm.conditionChange();
            }
        }
    });

    avalon.directive('fill', {
        update: function (value) {
            $(this.element).unbind();
            $(this.element).on('click', function () {
                vm.selectionPos ? false : vm.selection = getSelectedContents(value);
                if (vm.taskQues[value[0]].title === vm.selection && vm.selectionPos === 0) { return; }
                if (vm.selection && !vm.selection.match(/_{1,}/g)) {
                    var beforeText = vm.taskQues[value[0]].title.substring(vm.selectionPos, (0 - vm.selectionPos)).match(/_____/g);
                    var answer = vm.taskQues[value[0]][value[1]];
                    answer = answer === '' ? [] : answer.split('┼┼');
                    beforeText ? answer.splice(beforeText.length, 0, vm.selection) : answer.unshift(vm.selection);
                    answer = answer.join('┼┼');
                    vm.taskQues[value[0]][value[1]] = answer;
                    var title = vm.taskQues[value[0]].title;
                    vm.taskQues[value[0]].title = title.substring(vm.selectionPos, (0 - vm.selectionPos)) + '_____' + title.substring(vm.selectionPos + vm.selection.length);
                    vm.selectionPos = false;
                    $('#fillscore' + value[0]).change();
                }
            });
        }
    });

    avalon.directive('fillscore', {
        update: function (index) {
            this.element.value = vm.taskQues[index].score;
            this.element.id = 'fillscore' + index;
            $(this.element).on('change', function () {
                var value = $(this).val().match(/^[0-9]{1,}/);
                var answer = vm.taskQues[index].answer.split('┼┼');
                answer[0] === "" && answer.pop();
                vm.taskQues[index].score = parseInt(answer.length || 1) * parseInt(value);
                console.log(vm.taskQues[index].score);
            });
        }
    });

    avalon.directive('fillanswer', {
        update: function (value) {
            if (value[0]) {
                value[0] = value[0].split('┼┼');
                value[0] = value[0].join('、');
                this.element.value = value[0];
            } else {
                value[0] = '';
                vm.taskQues[value[1]].answer = '';
            }
        }
    });

    avalon.directive('fillmutlanswer', {
        update: function (value) {
            if (value[0] && value[0] !== '') {
                value[0] = value[0].split('┼┼');
                value[0] = value[0].join('、');
                value[1] ? this.element.innerHTML = value[0] : this.element.value = value[0];
            }         
        }
    });

    avalon.directive('selection', {
        update: function (value) {
            if (!document.onselectionchange && value === 4) {
                document.onselectionchange = function (e) {
                    if (window.getSelection) {
                        var selection = window.getSelection();
                        var txt = '';
                        try {
                            if (selection.anchorNode.parentElement === selection.focusNode.parentElement) {
                                txt = selection.anchorNode.parentElement.innerText.substring(selection.anchorOffset, selection.focusOffset);
                            }
                            if (txt !== '') {
                                vm.selection = txt;
                                vm.selectionPos = selection.anchorOffset >= selection.focusOffset ? selection.focusOffset : selection.anchorOffset;
                            }
                        } catch (err) {
                            vm.selection = null;
                            vm.selectionPos = null;
                        }
                    }
                };
            }
        }
    });

    avalon.directive('selectfile', {
        update: function (value) {
            $(this.element).unbind();
            $(this.element).click(function () {
                var anchorNode = window.getSelection().anchorNode;
                if (!(anchorNode.className === 'editable' || anchorNode.parentNode.className === 'editable') || vm.uploadStatus === 1) {
                    return;
                }
                document.getElementById('fileDiv').innerHTML = '<input type="file" value="" class="fileButton" id="fileObject">';
                $('#fileObject').bind('change', function () {
                    vm.fileObj = document.getElementById('fileObject').files[0];
                    document.getElementById('fileDiv').innerHTML = '';
                    var suffix = $(this).val().substring($(this).val().lastIndexOf('.') + 1);
                    if (suffix.match(/(jpg|jpeg|png)/i)) {
                        if ($('#' + value[1] + value[0]).html() === '新增题目' || $('#' + value[1] + value[0]).html() === '默认选项' || $('#' + value[1] + value[0]).html() === '默认答案') {
                            $('#' + value[1] + value[0]).html('');
                        }
                        (typeof value[0] !== 'number' || vm.taskQues[value[0]].type !== 4) && insertHtmlAtCaret('[正在上传...]');
                        if (typeof value[0] === 'number') {
                            vm.taskQues[value[0]][value[1]] = $('#' + value[1] + value[0]).html();
                            if (vm.taskQues[value[0]].type === 4){
                                vm.taskQues[value[0]][value[1]] = vm.taskQues[value[0]][value[1]] + '[正在上传...]';
                            }
                        } else {
                            value[0] = value[0].split('-');
                            vm.taskQues[parseInt(value[0][0])][value[1]].set(
                                parseInt(value[0][1]),
                                $('#' + value[1] + value[0][0] + '-' + value[0][1]).html()
                            );
                            value[0] = value[0].join('-');
                        }
                        vm.uploadResource($(this).val(), value);
                    } else {
                        vm.endUpload('文件格式不正确');
                    }
                    return;
                });
                $('#fileObject').click();
            });
        }
    });

    avalon.directive('editable', {
        update: function (value) {
            var element = this.element;
            $(this.element).unbind();
            $(this.element).on('focus', function () {
                ($(this).html() === '新增题目' ||　$(this).html() === '默认选项' ||　$(this).html() === '默认答案') && $(this).html('');
            });
            $(this.element).on('blur', function () {
                if ((value[1] === 'title' && $(this).html() === '') || (value[1] === 'choice' && $(this).html() === '') || (value[1] === 'answer' && $(this).html() === '')) {
                    value[1] === 'title' ? $(this).html('新增题目') : (value[1] === 'choice' ? $(this).html('默认选项') : $(this).html('默认答案'));
                }
                if (value[1] !== 'choice' && $(this).html() !== vm.taskQues[value[0]][value[1]]) {
                    $(this).html($(this).html().replace(/&nbsp;/ig, ''));
                    $(this).html($(this).html().replace(/  /ig, ' '));
                }
                if (typeof value[0] === 'number') {
                    vm.taskQues[value[0]][value[1]] = $(this).html();
                    if (vm.taskQues[value[0]].type === 4) {
                        var i, match = vm.taskQues[value[0]][value[1]].match(/<img.*?(?:>|\/>)/gi);
                        if (match) {
                            for (i in match) {
                                vm.taskQues[value[0]][value[1]] = vm.taskQues[value[0]][value[1]].replace(match[i], '');
                                vm.taskQues[value[0]][value[1]] = vm.taskQues[value[0]][value[1]] + match[i];
                            }
                        }
                        match = vm.taskQues[value[0]][value[1]].match(/<[^>]+>/gi);
                        if (match) {
                            for (i in match) {
                                if (!match[i].match(/<img.*?(?:>|\/>)/gi)) {
                                    vm.taskQues[value[0]][value[1]] = vm.taskQues[value[0]][value[1]].replace(match[i], '');
                                }
                            }
                        }
                    }
                } else {
                    value[0] = value[0].split('-');
                    vm.taskQues[parseInt(value[0][0])][value[1]].set(
                        parseInt(value[0][1]),
                        $(this).html()
                    );
                    value[0] = value[0].join('-');
                }
            });
            var beforeNum, afterNum, match;
            $(this.element).on('keydown', function (e) {
                match = $(this).html().match(/<img.*?(?:>|\/>)/gi);
                beforeNum = match ? match.length : 0;
            });
            $(this.element).on('keyup', function (e) {
                match = $(this).html().match(/<img.*?(?:>|\/>)/gi);
                afterNum = match ? match.length : 0;
                if (beforeNum > afterNum) {
                    $('#md5container').focus();
                    vm.window = null;
                }
            });
        }
    });

    avalon.directive('exec', {
        update: function (value) {
            $(this.element).unbind();
            $(this.element).click(function () {
                switch (value[0]) {
                    case 1:
                        document.execCommand('bold', false, null);
                        break;
                    case 2:
                        document.execCommand('italic', false, null);
                        break;
                    case 3:
                        document.execCommand('underline', false, null);
                        break;
                }
                if (typeof value[1] === 'number') {
                    vm.taskQues[value[1]][value[2]] = $('#' + value[2] + value[1]).html();
                } else {
                    value[1] = value[1].split('-');
                    vm.taskQues[parseInt(value[1][0])][value[2]].set(
                        parseInt(value[1][1]),
                        $('#' + value[2] + value[1][0] + '-' + value[1][1]).html()
                    );
                    value[1] = value[1].join('-');
                }
            });
        }
    });

    avalon.directive('questiontype', {
        update: function (value) {
            var type;
            switch (value) {
                case 1: type = '单选题';  break;
                case 2: type = '多选题';  break;
                case 3: type = '判断题';  break;
                case 4: type = '填空题';  break;
                case 5: type = '解答题';  break;
            }
            this.element.innerHTML = '题型：' + type;
        }
    });

    avalon.directive('importpage', {
        update: function (value) {
            if (value > 0) {
                this.element.style.display = 'block';
                var element = this.element;
                element.innerHTML = '';
                var p, i;
                for (i = 1; i <= value; ++i) {
                    p = document.createElement('p');
                    p.innerHTML = i;
                    vm.importPage === i && p.setAttribute('class', 'import_ques_content_page_active');
                    element.appendChild(p);
                }
                $(element).find('p').click(function () {
                    if ($(this).attr('class') !== 'import_ques_content_page_active') {
                        vm.importPage = parseInt($(this).html());
                        var self = this;
                        vm.conditionChange(function () {
                            $(element).find('p').removeClass('import_ques_content_page_active');
                            $(self).addClass('import_ques_content_page_active');
                        });
                    }
                });
            } else {
                this.element.style.display = 'none';
            }
        }
    });

    avalon.directive('questionindex', {
        update: function (value) {
            value = String.fromCharCode(value + 65);
            this.element.innerHTML = value + '. ';
        }
    });

    avalon.directive('papertype', {
        update: function (value) {
            if (vm.paperType[value[0]] === null) {
                vm.paperType[value[0]] = value[1];
            }
        }
    });

    avalon.directive('questiondifficult', {
        update: function (index) {
            var el = this.vmodels[1].taskQues[index];
            var element = this.element;
            var createStar = function (number) {
                element.innerHTML = '';
                for (var i = 1; i <= 3; ++i) {
                    var span = document.createElement('span');
                    span.setAttribute('value', i);
                    i > number ? span.innerHTML = '☆' : span.innerHTML = '★';
                    element.appendChild(span);
                }
                $(element).children().click(function () {
                    el.difficult = parseInt($(this).attr('value'));
                    createStar(el.difficult);
                });
            };
            createStar(el.difficult);
        }
    });

    avalon.directive('singlechoice', {
        update: function (value) {
            var element = this.element;
            var answer = vm.taskQues[value[0] - 1].answer.charCodeAt() - 65;
            element.setAttribute('value', value[1]);
            element.innerHTML = '';
            if (value[1] === answer) {
                var span = document.createElement('span');
                element.appendChild(span);
            } else {
                $(element).unbind('click');
                $(element).click(function () {
                    vm.taskQues[value[0] - 1].answer = String.fromCharCode(parseInt($(this).attr('value')) + 65);
                    vm.taskQues.splice(parseInt(value[0] - 1), 1, vm.taskQues[parseInt(value[0] - 1)]);
                });
            }
        }
    });

    avalon.directive('multichoice', {
        update: function (value) {
            var current = String.fromCharCode(value[1] + 65);
            var answer = vm.taskQues[value[0] - 1].answer.split('┼┼');
            this.element.setAttribute('value', value[1]);
            this.element.innerHTML = '';
            $(this.element).unbind('click');
            if (answer.indexOf(current) >= 0) {
                var span = document.createElement('span');
                this.element.appendChild(span);
                $(this.element).click(function () {
                    var answer = vm.taskQues[value[0] - 1].answer.split('┼┼');
                    answer.splice(answer.indexOf(String.fromCharCode(parseInt($(this).attr('value')) + 65)), 1);
                    vm.taskQues[value[0] - 1].answer = answer.join('┼┼');
                });
            } else {
                $(this.element).click(function () {
                    var answer = vm.taskQues[value[0] - 1].answer.split('┼┼');
                    (answer.length === 1 && answer[0] === '') && answer.shift();
                    answer.push(String.fromCharCode(parseInt($(this).attr('value')) + 65));
                    answer.sort(function (a, b) {
                        return a > b ? 1 : -1;
                    });
                    vm.taskQues[value[0] - 1].answer = answer.join('┼┼');
                });
            }
        }
    });

    avalon.directive('choicechange', {
        update: function (value) {
            var element = this.element;
            $(element).unbind('change');
            $(element).change(function () {
                vm.taskQues[value[0]].choice.set(value[1], $(this).val());
            });
        }
    });

    avalon.directive('selectclass', {
        update: function (id) {
            var element = this.element;
            (vm.selectClass.indexOf(id) >= 0) && $(element).addClass('publish_class_block_active');
            $(element).click(function () {
                vm.selectClassWarning = false;
                var className = $(this).attr('class').split(' ');
                if (className.indexOf('publish_class_block_active') >= 0) {
                    vm.selectClass.splice(vm.selectClass.indexOf(id), 1);
                    $(this).removeClass('publish_class_block_active');
                } else {
                    vm.selectClass.push(parseInt(id));
                    $(this).addClass('publish_class_block_active');
                }
            });
        }
    });

    vm.$watch('importDifficult', function (newVal, oldVal) {
        vm.importDifficult = newVal;
        vm.conditionChange();
    });

    vm.$watch('importType', function (newVal, oldVal) {
        vm.importType = newVal;
        vm.conditionChange();
    });

    vm.$watch('title', function () {
        vm.paperTitileWanring = false;
    });

    vm.$watch('imgZoom', function (newVal) {
        (!vm.editing) ? vm.imgZoom = newVal : vm.imgZoom = null;
        vm.enlarge();
    });

    var date = new Date();
    vm.pickdate = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + (date.getDate() + 1);

    function insertHtmlAtCaret (html) {
        var sel, range;
        if (window.getSelection) {
            sel = window.getSelection();
            if (sel.getRangeAt && sel.rangeCount) {
                range = sel.getRangeAt(0);
                range.deleteContents();
                var el = document.createElement("div");
                el.innerHTML = html;
                var frag = document.createDocumentFragment(), node, lastNode;
                while ( (node = el.firstChild) ) {
                    lastNode = frag.appendChild(node);
                }
                range.insertNode(frag);
                if (lastNode) {
                    range = range.cloneRange();
                    range.setStartAfter(lastNode);
                    range.collapse(true);
                    sel.removeAllRanges();
                    sel.addRange(range);
                }
            }
        } else if (document.selection && document.selection.type != "Control") {
            document.selection.createRange().pasteHTML(html);
        }
    }

    function getSelectedContents (value) {
        if (document.selection) {
            var selection = window.getSelection();
            vm.selectionPos = selection.anchorOffset >= selection.focusOffset ? selection.focusOffset : selection.anchorOffset;
            return document.selection.createRange().text.toString();
        } else if (window.getSelection) {
            var selection = window.getSelection();
            var range = selection.getRangeAt(0);
            var container = document.createElement('div');
            container.appendChild(range.cloneContents());
            vm.selectionPos = selection.anchorOffset >= selection.focusOffset ? selection.focusOffset : selection.anchorOffset;
            return window.getSelection().toString();
        } else if (document.getSelection) {
            var selection = window.getSelection();
            var range = selection.getRangeAt(0);
            var container = document.createElement('div');
            container.appendChild(range.cloneContents());
            return document.getSelection().toString();
        }
    }

    return vm;

});