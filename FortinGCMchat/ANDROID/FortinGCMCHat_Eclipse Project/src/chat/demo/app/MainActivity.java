package chat.demo.app;

import java.util.ArrayList;
import java.util.List;

import android.app.AlertDialog;
import android.app.Fragment;
import android.app.FragmentManager;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.res.Configuration;
import android.os.AsyncTask;
import android.os.Bundle;
import android.os.Handler;
import android.support.v4.view.GravityCompat;
import android.support.v4.widget.DrawerLayout;
import android.support.v7.app.ActionBarActivity;
import android.support.v7.app.ActionBarDrawerToggle;
import android.support.v7.widget.Toolbar;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.AdapterView;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;
import chat.demo.app.utils.DrawerAdapter;
import chat.demo.app.utils.DrawerItem;
import chat.demo.app.utils.ImageUtil;
import chat.demo.app.views.RoundedImageView;

import com.nostra13.universalimageloader.core.ImageLoader;
import com.nostra13.universalimageloader.core.ImageLoaderConfiguration;

public class MainActivity extends ActionBarActivity {

	private ListView mDrawerList;
	private List<DrawerItem> mDrawerItems;
	private DrawerLayout mDrawerLayout;
	private ActionBarDrawerToggle mDrawerToggle;
	private Handler mHandler;
	// Typeface normal, bold;
	private CharSequence mDrawerTitle;
	private CharSequence mTitle;
	SessionManager sesion;
	String name, profileurl, deviceid, logintype;
	int fragmentposition = 0;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_main);
		// normal = Typeface.createFromAsset(getAssets(), "normal.ttf");
		// bold = Typeface.createFromAsset(getAssets(), "bold.ttf");
		sesion = new SessionManager(this);

		if (getIntent().getAction().equals("splash")) {
			fragmentposition = 1;

		}

		if (getIntent().getAction().equals("search")) {

			fragmentposition = 2;
		}

		if (getIntent().getAction().equals("blockedusers")) {
			fragmentposition = 4;
		}
		if (getIntent().getAction().equals("newgroup")) {
			fragmentposition = 5;
		}
		if (getIntent().getAction().equals("settings")) {
			fragmentposition = 6;
		}

		name = sesion.firstname() + " " + sesion.lastname();

		deviceid = sesion.getdeviceid();
		logintype = sesion.getLogintype();

		Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
		setSupportActionBar(toolbar);
		mDrawerLayout = (DrawerLayout) findViewById(R.id.drawer_layout);
		mTitle = mDrawerTitle = getTitle();

		mDrawerList = (ListView) findViewById(R.id.list_view);

		mDrawerLayout.setDrawerShadow(R.drawable.drawer_shadow,
				GravityCompat.START);
		prepareNavigationDrawerItems();
		setAdapter();
		// mDrawerList.setAdapter(new DrawerAdapter(this, mDrawerItems));
		mDrawerList.setOnItemClickListener(new DrawerItemClickListener());

		mDrawerToggle = new ActionBarDrawerToggle(this, mDrawerLayout, toolbar,
				R.string.drawer_open, R.string.drawer_close) {
			public void onDrawerClosed(View view) {
				getSupportActionBar().setTitle(mTitle);
				invalidateOptionsMenu();
			}

			public void onDrawerOpened(View drawerView) {
				getSupportActionBar().setTitle(mDrawerTitle);
				invalidateOptionsMenu();
			}
		};
		mDrawerLayout.setDrawerListener(mDrawerToggle);

		mHandler = new Handler();

		if (savedInstanceState == null) {

			selectItem();

		}
	}

	private void setAdapter() {

		String url = sesion.getuserid();

		View headerView = null;

		headerView = prepareHeaderView(R.layout.header_navigation_drawer_1,
				url, name);

		BaseAdapter adapter = new DrawerAdapter(this, mDrawerItems);

		mDrawerList.addHeaderView(headerView);// Add header before adapter (for
												// pre-KitKat)
		mDrawerList.setAdapter(adapter);
	}

	private View prepareHeaderView(int layoutRes, String url, String name) {
		View headerView = getLayoutInflater().inflate(layoutRes, mDrawerList,
				false);
		RoundedImageView iv = (RoundedImageView) headerView
				.findViewById(R.id.image);
		TextView tv = (TextView) headerView.findViewById(R.id.txtname);
		ImageLoader.getInstance().init(
				ImageLoaderConfiguration.createDefault(this));
		String logintype = sesion.getLogintype();
		String profileurl = sesion.getProfilePic();

		if (profileurl != null) {
			if (logintype.equals("google")) {

				String googleurl = profileurl;

				ImageUtil.displayImage(iv, googleurl, null);

			} else if (logintype.equals("facebook")) {
				String userid = profileurl;

				String oppurl = DataManager.GetDirectURL(userid);

				ImageUtil.displayImage(iv, oppurl, null);

			} else if (logintype.equals("email")) {

				String photourl = DataManager.url + profileurl;

				ImageUtil.displayImage(iv, photourl, null);

			}
		}

		tv.setText(name);

		headerView.setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				Intent i = new Intent(MainActivity.this, UserProfile.class);
				DataManager.profileid = sesion.getuserid();
				startActivity(i);
				overridePendingTransition(0, 0);

			}
		});

		return headerView;
	}

	private void prepareNavigationDrawerItems() {
		mDrawerItems = new ArrayList<DrawerItem>();
		mDrawerItems.add(new DrawerItem(R.string.drawer_icon_home,
				R.string.drawer_title_home)); // home
		mDrawerItems.add(new DrawerItem(R.string.drawer_icon_search,
				R.string.drawer_title_search)); // search
		mDrawerItems.add(new DrawerItem(R.string.drawer_icon_myfriends,
				R.string.drawer_title_myfriends)); // friends
		mDrawerItems.add(new DrawerItem(R.string.drawer_icon_blockeduser,
				R.string.drawer_title_blockeduser)); // blocked user

		mDrawerItems.add(new DrawerItem(R.string.drawer_icon_newgroup,
				R.string.drawer_title_newgroup)); // new group
		mDrawerItems.add(new DrawerItem(R.string.drawer_icon_setting,
				R.string.drawer_title_settings)); // settings

		mDrawerItems.add(new DrawerItem(R.string.drawer_icon_share,
				R.string.drawer_title_share));
		mDrawerItems.add(new DrawerItem(R.string.drawer_icon_logout,
				R.string.drawer_title_logout));

	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		MenuInflater inflater = getMenuInflater();

		return super.onCreateOptionsMenu(menu);
	}

	@Override
	public boolean onPrepareOptionsMenu(Menu menu) {
		return super.onPrepareOptionsMenu(menu);
	}

	@Override
	public boolean onOptionsItemSelected(MenuItem item) {
		if (mDrawerToggle.onOptionsItemSelected(item)) {
			return true;
		}
		return super.onOptionsItemSelected(item);
	}

	private class DrawerItemClickListener implements
			ListView.OnItemClickListener {
		@Override
		public void onItemClick(AdapterView<?> parent, View view, int position,
				long id) {
			fragmentposition = position;
			selectItem();
		}
	}

	private void selectItem() {

		if (fragmentposition < 1) {
			return;
		}

		Fragment fragment = getFragmentByDrawerTag(fragmentposition);
		commitFragment(fragment);

		String drawerTitle = getString(mDrawerItems.get(fragmentposition - 1)
				.getTitle());

		mDrawerList.setItemChecked(fragmentposition, true);
		setTitle(mDrawerItems.get(fragmentposition - 1).getTitle());
		mDrawerLayout.closeDrawer(mDrawerList);
	}

	private Fragment getFragmentByDrawerTag(int position) {
		Fragment fragment = MyMessageActivity.newInstance();
		if (fragmentposition == 1) {
			fragment = MyMessageActivity.newInstance();
		} else if (fragmentposition == 2) {
			DataManager.action = "search";

			fragment = AllUsersActivity.newInstance();
		} else if (fragmentposition == 3) {
			DataManager.action = "friends";
			fragment = AllUsersActivity.newInstance();
		} else if (fragmentposition == 4) {
			fragment = BlockedUsers.newInstance();
		} else if (fragmentposition == 5) {
			fragment = AddGroup.newInstance();
		} else if (fragmentposition == 6) {

			fragment = SettingsActivity.newInstance();
		} else if (fragmentposition == 7) {
			shareapp();
			fragment = MyMessageActivity.newInstance();
		} else {
			fragment = MyMessageActivity.newInstance();
			new logoutuser().execute();
		}

		return fragment;
	}

	public void shareapp() {
		Intent sharingIntent = new Intent(Intent.ACTION_SEND);
		sharingIntent.setType("text/plain");
		sharingIntent.putExtra(android.content.Intent.EXTRA_TEXT, "Share");
		startActivity(Intent.createChooser(sharingIntent, "Share using"));
	}

	private class CommitFragmentRunnable implements Runnable {

		private Fragment fragment;

		public CommitFragmentRunnable(Fragment fragment) {
			this.fragment = fragment;
		}

		@Override
		public void run() {
			FragmentManager fragmentManager = getFragmentManager();
			fragmentManager.beginTransaction()
					.replace(R.id.content_frame, fragment).commit();
		}
	}

	public void commitFragment(Fragment fragment) {
		mHandler.post(new CommitFragmentRunnable(fragment));
	}

	@Override
	public void setTitle(int titleId) {
		setTitle(getString(titleId));
	}

	@Override
	public void setTitle(CharSequence title) {
		mTitle = title;
		getSupportActionBar().setTitle(mTitle);
	}

	@Override
	protected void onPostCreate(Bundle savedInstanceState) {
		super.onPostCreate(savedInstanceState);
		mDrawerToggle.syncState();
	}

	@Override
	public void onConfigurationChanged(Configuration newConfig) {
		super.onConfigurationChanged(newConfig);
		mDrawerToggle.onConfigurationChanged(newConfig);
	}

	@Override
	protected void onResume() {
		super.onResume();
		new getuserprofile().execute();
	}

	@Override
	public void onBackPressed() {

		if (fragmentposition == 1) {
			new AlertDialog.Builder(this)
					.setTitle("Really Exit?")
					.setMessage("Are you sure you want to exit?")
					.setNegativeButton(android.R.string.no, null)
					.setPositiveButton(android.R.string.yes,
							new DialogInterface.OnClickListener() {

								public void onClick(DialogInterface arg0,
										int arg1) {
									Intent intent = new Intent(
											Intent.ACTION_MAIN);
									intent.addCategory(Intent.CATEGORY_HOME);
									intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
									finish();
									startActivity(intent);
								}
							}).create().show();
		} else {
			Intent i = new Intent(MainActivity.this, MainActivity.class);
			i.setAction("splash");
			finish();
			startActivity(i);
		}

	}

	public class getuserprofile extends AsyncTask<String, Void, String> {
		boolean response = false;

		@Override
		protected void onPreExecute() {

		}

		@Override
		protected String doInBackground(String... params) {

			response = APIManager.getuserprofile(getApplicationContext(),
					sesion.getuserid(), deviceid, sesion.getuserid());

			return "";

		}

		@Override
		protected void onPostExecute(String result) {

			if (response) {

				sesion.setProfilepic(DataManager.alluserlist.get(0)
						.getProfilepic());
			}

		}

		@Override
		protected void onProgressUpdate(Void... values) {

		}
	}

	private class logoutuser extends AsyncTask<String, Void, String> {

		@Override
		protected void onPreExecute() {
		}

		@Override
		protected String doInBackground(String... params) {

			APIManager.logout(sesion.getuserid(), deviceid);

			return "Executed";
		}

		@Override
		protected void onPostExecute(String result) {

			if (DataManager.status.equals("1")) {

				sesion.logoutUser();

				Toast.makeText(getApplicationContext(),
						"Logout Successfully..", Toast.LENGTH_LONG).show();

			}
		}

		@Override
		protected void onProgressUpdate(Void... values) {

		}
	}
}