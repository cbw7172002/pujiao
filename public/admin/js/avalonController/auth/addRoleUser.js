define([], function() {

	var role = avalon.define({
		$id: 'addRoleUser',
		getData: function(url, model, data, method, callback) {
			$.ajax({
				type: method || 'GET',
				url: url,
				data: data,
				dataType: "json",
				success: function(response) {
					if (response.type) {
						role[model] = response.data;
					}
					model == 'userList' && role.checkUser.removeAll();
				},
				error: function(error) {
					alert('数据加载失败');
				}
			});
		},
		departmentList: [],
		postList: [],
		userList: [],
		checkDepartment: '',
		checkPost: '',
		checkUser: [],
		sendUserList: '',
		getList: function(checkPost, id) {
			if (checkPost) {
				role.getData('/admin/auth/getList/'+ id, 'postList');
			}
			role.getData('/admin/auth/getUser/'+ checkPost +'/'+ id +'/'+ role.roleID, 'userList');
			role.userList.removeAll();
		},
		selectUser: function(value) {
			if (this.checked) {
				role.checkUser.push(value);
			} else {
				for (var i = 0; i < role.checkUser.size(); i++) {
					role.checkUser[i] == value && role.checkUser.removeAt(i);
				}
			}
		}
	});

	return role;

});