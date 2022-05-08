package chat.demo.app;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import android.app.Activity;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v7.app.ActionBarActivity;
import android.support.v7.widget.Toolbar;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.TextView;
import chat.demo.app.utils.ImageUtil;
import chat.demo.app.views.RoundedImageView;

import com.applidium.shutterbug.FetchableImageView;
import com.facebook.widget.ProfilePictureView;

public class UserProfile extends ActionBarActivity {

	String name, city, country, gender, logintype;
	RoundedImageView img_icon;

	ArrayList<UserPojo> userlist = new ArrayList<UserPojo>();
	String myusername, deviceid, profileid;
	SessionManager session;
	TextView txtname, txtgender, txtlocation, txtstatus, txtupdateprofile;
	String status = "";

	// private static final String AD_UNIT_ID = DataManager.bannerid;
	// private AdView adView;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_profile);

		// adView = new AdView(this);
		// adView.setAdSize(AdSize.BANNER);
		// adView.setAdUnitId(AD_UNIT_ID);
		// AdRequest adRequest = new AdRequest.Builder().build();
		// adView.loadAd(adRequest);
		// LinearLayout ll = (LinearLayout) findViewById(R.id.ad);
		// ll.addView(adView);

		Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
		setSupportActionBar(toolbar);

		getSupportActionBar().setDisplayShowTitleEnabled(true);
		getSupportActionBar().setHomeAsUpIndicator(
				getResources().getDrawable(R.drawable.back));
		// invalidateOptionsMenu();

		session = new SessionManager(this);
		profileid = DataManager.profileid;
		deviceid = session.getdeviceid();
		myusername = session.getuserid();
		widgets();

		new getuserprofile().execute();

	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		// getMenuInflater().inflate(R.menu.individual, menu);
		return true;
	}

	@Override
	public boolean onOptionsItemSelected(MenuItem item) {
		// Handle action bar item clicks here. The action bar will
		// automatically handle clicks on the Home/Up button, so long
		// as you specify a parent activity in AndroidManifest.xml.
		int id = item.getItemId();
		if (id == android.R.id.home) {

			Intent i = new Intent(this, MainActivity.class);
			i.setAction("splash");
			finish();
			startActivity(i);
			overridePendingTransition(0, 0);
			return true;
		}
		return super.onOptionsItemSelected(item);
	}

	public void widgets() {
		txtstatus = (TextView) findViewById(R.id.txtstatus);
		txtname = (TextView) findViewById(R.id.txtname);
		txtgender = (TextView) findViewById(R.id.txtgender);
		txtlocation = (TextView) findViewById(R.id.txtlocation);
		txtupdateprofile = (TextView) findViewById(R.id.txtupdateprofile);
		
		img_icon = (RoundedImageView) findViewById(R.id.imageView1);

		txtupdateprofile.setVisibility(View.GONE);
		
		txtupdateprofile.setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				Intent i = new Intent(UserProfile.this, UpdateProfile.class);
				finish();
				startActivity(i);
				overridePendingTransition(0, 0);
			}
		});
	}

	@Override
	public void onBackPressed() {
		Intent i = new Intent(this, MainActivity.class);
		i.setAction("splash");
		finish();
		startActivity(i);
		overridePendingTransition(0, 0);
	}

	public void displaydata() {
		String selectedid = userlist.get(0).getUserid();

		if (selectedid.equals(myusername)) {
			txtupdateprofile.setVisibility(View.VISIBLE);
		} else {
			txtupdateprofile.setVisibility(View.GONE);
		}
		status = userlist.get(0).getStatus();
		logintype = userlist.get(0).getLogintype();
		name = userlist.get(0).getFirstname() + " "
				+ userlist.get(0).getLastname();
		gender = userlist.get(0).getGender().toUpperCase();
		String location = userlist.get(0).getCity();

		if (location.contains(",")) {
			List<String> elephantList = Arrays.asList(location.split(","));

			city = elephantList.get(0).toString().toUpperCase();
		} else {
			city = location;
		}

		country = userlist.get(0).getCountry().toUpperCase();

		if (logintype.equals("google")) {

			String googleurl = userlist.get(0).getProfilepic();

			ImageUtil.displayImage(img_icon, googleurl, null);
		} else if (logintype.equals("email")) {
			String url = userlist.get(0).getProfilepic();

			String photourl = DataManager.url + url;
			ImageUtil.displayImage(img_icon, photourl, null);
		} else {
			String userid = userlist.get(0).getUserid();
			
			String oppurl = DataManager.GetDirectURL(userid);
			
			ImageUtil.displayImage(img_icon, oppurl, null);
			
		}
		
		

		txtname.setText("" + name);
		txtgender.setText("" + gender);
		if (gender.equals("")) {
			txtgender.setText("");
		}

		if (city.equals("")) {
			if (country.equals("")) {

				txtlocation.setText("");
			} else {
				txtlocation.setText("" + city);
			}
		} else {
			if (country.equals("")) {
				txtlocation.setText("" + city);
			} else {

				txtlocation.setText("" + city + ", " + country);

			}
		}

		txtstatus.setText("" + status);
	}

	public class getuserprofile extends AsyncTask<String, Void, String> {
		boolean response = false;

		@Override
		protected void onPreExecute() {

		}

		@Override
		protected String doInBackground(String... params) {

			response = APIManager.getuserprofile(UserProfile.this, myusername,
					deviceid, DataManager.profileid );

			return "";

		}

		@Override
		protected void onPostExecute(String result) {

			if (DataManager.status.equalsIgnoreCase("1")) {
				userlist = DataManager.alluserlist;
				displaydata();
			} else if (DataManager.status.equalsIgnoreCase("false")) {
				session.logoutUser();
			}

		}

		@Override
		protected void onProgressUpdate(Void... values) {

		}
	}
}
