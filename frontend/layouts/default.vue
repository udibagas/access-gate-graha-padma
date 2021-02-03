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
					:default-active="$route.path"
				>
					<el-menu-item index="/">Home</el-menu-item>
					<el-menu-item index="/member">Member</el-menu-item>
					<el-menu-item index="/access-gate">Access Gate</el-menu-item>
					<el-menu-item index="/camera">Camera</el-menu-item>
					<el-menu-item index="/snapshot">Snapshot</el-menu-item>
					<!-- <el-menu-item index="/user">User</el-menu-item> -->
					<!-- <el-menu-item index="/setting">Setting</el-menu-item> -->
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
      showProfileDialog: false
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
	line-height: 60px;
	display: flex;
	justify-content: space-between;
	border-bottom: 1px solid #ddd;
	background: navy;
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
