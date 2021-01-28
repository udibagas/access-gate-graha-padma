<template>
	<el-container>
		<el-header>
			<div class="title">GRAHA PADMA ACCESS GATE</div>
			<div style="display: flex">
				<el-menu
					mode="horizontal"
					router
					background-color="transparent"
					text-color="white"
					active-text-color="red"
				>
					<el-menu-item index="/">Home</el-menu-item>
					<el-menu-item index="/setting">Setting</el-menu-item>
					<el-menu-item index="/member">Member</el-menu-item>
					<el-menu-item index="/user">User</el-menu-item>
				</el-menu>
				<el-dropdown>
					<span class="el-dropdown-link">
						<el-avatar
							:size="30"
							icon="el-icon-user"
							style="margin: 15px 0 15px 20px"
						></el-avatar>
					</span>
					<el-dropdown-menu slot="dropdown">
						<el-dropdown-item icon="el-icon-edit">Profile</el-dropdown-item>
						<el-dropdown-item icon="el-icon-delete">Logout</el-dropdown-item>
					</el-dropdown-menu>
				</el-dropdown>
			</div>
		</el-header>
		<el-main>
			<Nuxt />
		</el-main>
	</el-container>
</template>

<style lang="css">
* {
	font-family: Arial, Helvetica, sans-serif;
}

body {
	margin: 0;
	padding: 0;
}

.el-header {
	line-height: 60px;
	display: flex;
	justify-content: space-between;
	border-bottom: 1px solid #ddd;
	background: navy;
}

.el-main {
	height: calc(100vh - 60px);
	background: #f1f5f8;
	padding: 0px;
}

.title {
	font-size: 16px;
	font-weight: bold;
	color: white;
}

.el-menu.el-menu--horizontal {
	border-bottom: none;
}

.el-table td,
.el-table th {
	padding: 8px 0;
}

.flex {
	display: flex;
}

.flex-grow {
	flex-grow: 1;
}
</style>
