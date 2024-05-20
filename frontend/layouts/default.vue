<template>
	<el-container>
		<el-header>
			<img src="/logo.png" style="height: 60px; margin-right: 20px" alt="" />
			<div class="title flex-grow-1">Gate Akses Cluster Adenia</div>
			<div class="d-flex">
				<el-menu
					mode="horizontal"
					router
					background-color="transparent"
					text-color="white"
					active-text-color="#d70082"
					:default-active="$route.path"
				>
					<el-menu-item
						v-for="(menu, index) in menus.filter((m) =>
							m.roles.includes($auth.user.role)
						)"
						:key="index"
						:index="menu.link"
						>{{ menu.label }}</el-menu-item
					>
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
						<el-dropdown-item
							icon="el-icon-user"
							@click.native.prevent="profile"
							>Profile</el-dropdown-item
						>
						<el-dropdown-item
							icon="el-icon-arrow-right"
							@click.native.prevent="logout"
							>Logout</el-dropdown-item
						>
					</el-dropdown-menu>
				</el-dropdown>
			</div>
		</el-header>
		<el-main>
			<Nuxt />
		</el-main>

		<ProfileForm :show="showProfileDialog" @close="showProfileDialog = false" />
	</el-container>
</template>

<script>
export default {
  middleware: ['auth'],

  data() {
    return {
      showProfileDialog: false,
      menus: [
        {label: 'Home', link: '/', roles: [0, 1]},
        // {label: 'Log', link: '/access-log', roles: [0, 1]},
        {label: 'Snapshot', link: '/snapshot', roles: [0, 1]},
        {label: 'Member', link: '/member', roles: [0, 1]},
        {label: 'Gate', link: '/access-gate', roles: [1]},
        {label: 'Camera', link: '/camera', roles: [1]},
        {label: 'User', link: '/user', roles: [1]},
        {label: 'Backup', link: '/backup', roles: [1]},
        // {label: 'Setting', link: '/setting', roles: [1]},
      ]
    }
  },

  methods: {
    logout() {
			this.$auth.logout()
			this.$router.push('/login')
    },

    profile() {
      this.showProfileDialog = true;
    }
  }
}
</script>

<style lang="css" scoped>
body {
	margin: 0;
	padding: 0;
}

.el-header {
	padding-left: 0;
	line-height: 60px;
	display: flex;
	/* justify-content: space-between; */
	border-bottom: 1px solid #ddd;
	background: #22a07c;
}

.el-main {
	height: calc(100vh - 60px);
	padding: 0px;
}

.title {
	font-size: 20px;
	font-weight: bold;
	color: white;
}

.el-menu.el-menu--horizontal {
	border-bottom: none;
}
</style>
