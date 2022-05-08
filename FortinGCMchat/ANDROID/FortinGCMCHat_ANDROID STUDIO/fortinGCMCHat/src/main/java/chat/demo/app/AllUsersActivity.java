package chat.demo.app;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.TimeZone;

import android.app.Fragment;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.AsyncTask;
import android.os.Bundle;
import android.preference.PreferenceManager;
import android.view.KeyEvent;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;
import chat.demo.app.utils.ImageUtil;
import chat.demo.app.views.RoundedImageView;

import com.applidium.shutterbug.FetchableImageView;

public class AllUsersActivity extends Fragment {

	public static AllUsersActivity newInstance() {
		return new AllUsersActivity();
	}
	ListView lv;
	private ProgressDialog progress;
	String myusername, deviceid, selecteduser;
	SessionManager session;
	DbUsers dbuser;
	ArrayList<UserPojo> userlist = new ArrayList<UserPojo>();
	GPSTracker gps;
	String strLatitude, strLongitude, distance;
	SharedPreferences prefs;
	double longitude = 0, latitude = 0;
	// private static final String AD_UNIT_ID = DataManager.bannerid;
	// private AdView adView;
	DbMessage dbmsg;
	boolean myfriends = false;
	EditText etsearch;
	Button btnsearch;
	String search = "";
	Context context;

	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container,
			Bundle savedInstanceState) {
		View rootView = inflater.inflate(
				R.layout.list_activity_peer_all_message, container, false);

		context = getActivity();

		etsearch = (EditText) rootView.findViewById(R.id.etsearch);

		btnsearch = (Button) rootView.findViewById(R.id.btnsearch);
		dbmsg = new DbMessage(context);
		// adView = new AdView(this);
		// adView.setAdSize(AdSize.BANNER);
		// adView.setAdUnitId(AD_UNIT_ID);
		// AdRequest adRequest = new AdRequest.Builder().build();
		// adView.loadAd(adRequest);
		// LinearLayout ll = (LinearLayout) findViewById(R.id.ad);
		// ll.addView(adView);

		prefs = PreferenceManager.getDefaultSharedPreferences(context);

		distance = prefs.getString("distance", "200");

		// distance = DataManager.searchdistance;
		lv = (ListView) rootView.findViewById(R.id.peerMsgList);
		session = new SessionManager(context);
		gps = new GPSTracker(context);
		deviceid = session.getdeviceid();
		myusername = session.getuserid();
		
		System.out.println("userid---"+myusername);

		dbuser = new DbUsers(context);

		String action = DataManager.action;

		if (action.equals("friends")) {
			myfriends = true;
			new getfriends().execute();

		} else if (action.equals("search")) {
			myfriends = false;

			if (gps.canGetLocation()) {

				strLatitude = String.valueOf(gps.getLatitude());
				strLongitude = String.valueOf(gps.getLongitude());

				new getnearbyfriends().execute();
			} else {
				strLatitude = "";
				strLongitude = "";
			}
		}

		lv.setOnItemClickListener(new OnItemClickListener() {

			@Override
			public void onItemClick(AdapterView<?> arg0, View view,
					int position, long arg3) {
				// TODO Auto-generated method stub
				TextView sub_name = (TextView) view
						.findViewById(R.id.list_am_name_bottom);
				TextView name = (TextView) view
						.findViewById(R.id.list_am_name_top);
				Intent i = new Intent(context, IndividualChat.class);
				DataManager.senderid = sub_name.getTag().toString();
				DataManager.fullname = name.getText().toString();
				startActivity(i);

			}
		});

		btnsearch.setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				search = etsearch.getText().toString();

				if (search.length() < 1) {
					Toast.makeText(context, "Enter any search word",
							Toast.LENGTH_LONG).show();
				} else {
					if (userlist.size() > 0) {
						userlist.clear();
					}
					new searchfriends().execute();
				}
			}
		});

		etsearch.setOnKeyListener(new View.OnKeyListener() {

			@Override
			public boolean onKey(View v, int keyCode, KeyEvent event) {
				// If the event is a key-down event on the "enter" button
				if ((event.getAction() == KeyEvent.ACTION_DOWN)
						&& (keyCode == KeyEvent.KEYCODE_ENTER)) {
					// Perform action on Enter key press
					search = etsearch.getText().toString();

					if (search.length() < 1) {
						Toast.makeText(context, "Enter any search word",
								Toast.LENGTH_LONG).show();
					} else {
						if (userlist.size() > 0) {
							userlist.clear();
						}
						new searchfriends().execute();
					}
					return true;
				}
				return false;
			}
		});

		return rootView;
	}

	// @Override
	// public boolean onCreateOptionsMenu(Menu menu) {
	// // Inflate the menu; this adds items to the action bar if it is present.
	// getMenuInflater().inflate(R.menu.chat, menu);
	// return true;
	// }
	//
	// @Override
	// public boolean onOptionsItemSelected(MenuItem item) {
	// // Handle action bar item clicks here. The action bar will
	// // automatically handle clicks on the Home/Up button, so long
	// // as you specify a parent activity in AndroidManifest.xml.
	// int id = item.getItemId();
	// if (id == R.id.newchat) {
	//
	// Intent i = new Intent(this,
	// AllUsersActivity.class);
	// i.setAction("friends");
	// finish();
	// startActivity(i);
	//
	// return true;
	// }else if (id == R.id.newgroup) {
	//
	// Intent i = new Intent(this, AddGroup.class);
	// finish();
	// startActivity(i);
	// return true;
	// }else if (id == R.id.blockusers) {
	//
	// Intent i = new Intent(context, BlockedUsers.class);
	// finish();
	// startActivity(i);
	// return true;
	// } else if (id == R.id.settings) {
	//
	// Intent i = new Intent(context,
	// SettingsActivity.class);
	// finish();
	// startActivity(i);
	// return true;
	// }else if (id == android.R.id.home) {
	// Intent i = new Intent(this, MyMessageActivity.class);
	// finish();
	// startActivity(i);
	// return true;
	// }
	// return super.onOptionsItemSelected(item);
	// }

	// @Override
	// public void onBackPressed() {
	// Intent i = new Intent(context, MainActivity.class);
	//
	// startActivity(i);
	// overridePendingTransition(0, 0);
	// }

	public class searchfriends extends AsyncTask<String, Void, String> {
		boolean response = false;

		@Override
		protected void onPreExecute() {
			progress = ProgressDialog.show(context, "Getting Users...",
					"Please wait....");

		}

		@Override
		protected String doInBackground(String... params) {

			response = APIManager.searchfriend(context, myusername, search);

			return "";

		}

		@Override
		protected void onPostExecute(String result) {

			progress.dismiss();
			if (DataManager.status.equalsIgnoreCase("1")) {

				userlist = DataManager.alluserlist;
				ImageAdapter img = new ImageAdapter(context);
				img.notifyDataSetChanged();
				lv.setAdapter(img);
			} else if (DataManager.status.equalsIgnoreCase("false")) {
				session.logoutUser();
			}

		}

		@Override
		protected void onProgressUpdate(Void... values) {

		}
	}

	public class getfriends extends AsyncTask<String, Void, String> {
		boolean response = false;

		@Override
		protected void onPreExecute() {
			progress = ProgressDialog.show(context, "Getting Users...",
					"Please wait....");

		}

		@Override
		protected String doInBackground(String... params) {

			response = APIManager.getmyfriends(context, myusername, deviceid);

			return "";

		}

		@Override
		protected void onPostExecute(String result) {

			progress.dismiss();
			if (DataManager.status.equalsIgnoreCase("1")) {
				userlist = DataManager.alluserlist;
				ImageAdapter img = new ImageAdapter(context);
				img.notifyDataSetChanged();
				lv.setAdapter(img);
			} else if (DataManager.status.equalsIgnoreCase("false")) {
				session.logoutUser();
			}

		}

		@Override
		protected void onProgressUpdate(Void... values) {

		}
	}

	public class getnearbyfriends extends AsyncTask<String, Void, String> {
		boolean response = false;

		@Override
		protected void onPreExecute() {
			progress = ProgressDialog.show(context, "Getting Users...",
					"Please wait....");

		}

		@Override
		protected String doInBackground(String... params) {

			response = APIManager.getnearbyfriends(context, myusername,
					deviceid, strLongitude, strLatitude, distance);

			return "";

		}

		@Override
		protected void onPostExecute(String result) {

			progress.dismiss();
			if (DataManager.status.equalsIgnoreCase("1")) {
				userlist = DataManager.alluserlist;
				ImageAdapter img = new ImageAdapter(context);
				img.notifyDataSetChanged();
				lv.setAdapter(img);
			} else if (DataManager.status.equalsIgnoreCase("false")) {
				session.logoutUser();
			} else {
				Toast.makeText(
						context,
						"No User found. Please increase your search radius from Settings.",
						Toast.LENGTH_LONG).show();
			}

		}

		@Override
		protected void onProgressUpdate(Void... values) {

		}
	}

	public class ImageAdapter extends BaseAdapter {

		private LayoutInflater mInflater;
		ArrayList<UserPojo> amList = DataManager.alluserlist;

		public ImageAdapter(Context c) {

			mInflater = LayoutInflater.from(c);

		}

		@Override
		public int getCount() {

			return amList.size();

		}

		@Override
		public Object getItem(int position) {
			return null;
		}

		@Override
		public long getItemId(int position) {
			return 0;
		}

		@Override
		public View getView(int position, View v, ViewGroup parent) {

			ViewHolder holder = null;

			if (v == null) {
				holder = new ViewHolder();
				v = mInflater.inflate(R.layout.all_user_row, null);

				holder.name = (TextView) v.findViewById(R.id.list_am_name_top);
				holder.sub_name = (TextView) v
						.findViewById(R.id.list_am_name_bottom);
				holder.content = (TextView) v
						.findViewById(R.id.list_am_content);
				holder.img_icon = (RoundedImageView) v
						.findViewById(R.id.imageView1);
			
				holder.btnaddfriend = (Button) v
						.findViewById(R.id.btnaddfriend);

				holder.btnaddfriend.setOnClickListener(new OnClickListener() {

					@Override
					public void onClick(View v) {
						// TODO Auto-generated method stub
						selecteduser = v.getTag().toString();
						new sendindividualmessage().execute();
					}
				});
				v.setTag(holder);

			} else {
				holder = (ViewHolder) v.getTag();
			}

			if (myfriends) {
				holder.btnaddfriend.setVisibility(View.GONE);
			}

			holder.name.setText(amList.get(position).getFirstname() + " "
					+ amList.get(position).getLastname());
			holder.name.setTag("individual");
			holder.btnaddfriend.setTag("" + amList.get(position).getUserid());
			holder.sub_name.setText(amList.get(position).getStatus());
			holder.sub_name.setTag(amList.get(position).getUserid());
			String logintype = amList.get(position).getLogintype();
			if (logintype.equals("google")) {
				String userid = amList.get(position).getUserid();

				String googleurl = amList.get(position).getProfilepic();

				ImageUtil.displayImage(holder.img_icon, googleurl, null);
	
			
			} else if (logintype.equals("facebook")) {
				String userid = amList.get(position).getUserid();
				
				String oppurl = DataManager.GetDirectURL(userid);
				
				ImageUtil.displayImage(holder.img_icon, oppurl, null);
				
				
			
			} else if (logintype.equals("email")) {

				String photourl = DataManager.url
						+ amList.get(position).getProfilepic();

				ImageUtil.displayImage(holder.img_icon, photourl, null);
				
			}

			holder.content.setText(amList.get(position).getCity() + ", "
					+ amList.get(position).getCountry());

			return v;
		}

		class ViewHolder {
			TextView name, sub_name, content;
			RoundedImageView img_icon;
			Button btnaddfriend;
		}

		// public void tableChanged(AllUsersActivity arg0) {
		// this.notifyDataSetChanged();
		// }
	}

	public static String getCurrentTimeStamp() {
		SimpleDateFormat sdfDate = new SimpleDateFormat("yyyy-MM-dd");
		sdfDate.setTimeZone(TimeZone.getTimeZone("GMT+8"));
		Date now = new Date();
		String strDate = sdfDate.format(now);
		return strDate;
	}

	public class sendindividualmessage extends AsyncTask<String, Void, String> {
		boolean response = false;

		@Override
		protected void onPreExecute() {

		}

		@Override
		protected String doInBackground(String... params) {

			response = APIManager.addasfriend(myusername, selecteduser,
					"Added as Friend", deviceid, "text");

			return "";

		}

		@Override
		protected void onPostExecute(String result) {

			if (DataManager.status.equals("false")) {
				session.logoutUser();
			} else if (DataManager.status.equals("block")) {
				Toast.makeText(
						context,
						"You have blocked this user. You can not message this user.",
						Toast.LENGTH_LONG).show();
			} else if (DataManager.status.equals("already")) {
				Toast.makeText(context,
						"This user already in your friend's list.",
						Toast.LENGTH_LONG).show();
			} else {

				new getuserprofile().execute();
			}
		}

		@Override
		protected void onProgressUpdate(Void... values) {

		}
	}

	public class getuserprofile extends AsyncTask<String, Void, String> {
		boolean response = false;

		@Override
		protected void onPreExecute() {

		}

		@Override
		protected String doInBackground(String... params) {

			response = APIManager.getuserprofile(context, myusername, deviceid,
					selecteduser);

			return "";

		}

		@Override
		protected void onPostExecute(String result) {

			if (DataManager.status.equalsIgnoreCase("1")) {
				Toast.makeText(context, "Friend Added.", Toast.LENGTH_LONG)
						.show();
				userlist = DataManager.alluserlist;

				String logintype = userlist.get(0).getLogintype();
				String name = userlist.get(0).getFirstname() + " "
						+ userlist.get(0).getLastname();
				Date todaydate = new Date();

				String date = String.valueOf(todaydate.getTime());
				dbmsg.addContact(new Message(name, "", "Added as Friend",
						myusername, selecteduser, date, logintype,
						"individual", "yes", "", "yes", "text"));
			} else if (DataManager.status.equalsIgnoreCase("false")) {
				session.logoutUser();
			}

		}

		@Override
		protected void onProgressUpdate(Void... values) {

		}
	}
}
